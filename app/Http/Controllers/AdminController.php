<?php

namespace App\Http\Controllers;

use App\Models\Bundling;
use App\Models\BundlingEvaluation;
use App\Models\Criteria;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vendor;
use App\Services\MarcosService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected MarcosService $marcosService;

    public function __construct(MarcosService $marcosService)
    {
        $this->marcosService = $marcosService;
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_vendors' => Vendor::count(),
            'total_bundlings' => Bundling::count(),
            'total_orders' => Order::count(),
            'pending_payments' => Payment::where('payment_status', 'pending_verification')->count(),
            'total_revenue' => Order::whereIn('status', ['paid', 'completed'])->sum('total_price'),
        ];

        // Chart 1: Distribusi Status Pesanan (Donut)
        $statusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Chart 2: Transaksi per Mitra Vendor Teratas (Batang)
        $vendorOrderStats = Vendor::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($v) {
                return [
                    'name' => $v->store_name,
                    'orders_count' => $v->orders_count,
                    'revenue' => (float) $v->orders()->whereIn('status', ['paid', 'completed'])->sum('total_price'),
                ];
            });

        $recentOrders = Order::with(['customer', 'vendor', 'bundling', 'payment'])
            ->latest()
            ->take(6)
            ->get();

        $marcosData = $this->marcosService->calculateRanking();
        $topRankings = array_slice($marcosData['rankings'] ?? [], 0, 5);

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topRankings', 'statusCounts', 'vendorOrderStats'));
    }

    public function criterias()
    {
        $criterias = Criteria::orderBy('code')->get();
        $totalWeight = $criterias->sum('weight');

        return view('admin.criterias', compact('criterias', 'totalWeight'));
    }

    public function updateCriteria(Request $request, $id)
    {
        $criteria = Criteria::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:benefit,cost'],
            'weight' => ['required', 'numeric', 'min:0.01', 'max:1'],
            'description' => ['nullable', 'string'],
        ]);

        $criteria->update($validated);

        return back()->with('success', 'Kriteria ' . $criteria->code . ' berhasil diperbarui.');
    }

    public function evaluations()
    {
        $criterias = Criteria::orderBy('code')->get();
        $bundlings = Bundling::with(['vendor', 'evaluations'])->get();

        return view('admin.evaluations', compact('criterias', 'bundlings'));
    }

    public function updateEvaluations(Request $request)
    {
        $scores = $request->input('scores', []);

        foreach ($scores as $bundlingId => $criteriaScores) {
            foreach ($criteriaScores as $criteriaId => $score) {
                BundlingEvaluation::updateOrCreate(
                    [
                        'bundling_id' => $bundlingId,
                        'criteria_id' => $criteriaId,
                    ],
                    [
                        'score' => (int) $score,
                    ]
                );
            }
        }

        return back()->with('success', 'Matriks evaluasi kriteria berhasil disimpan & diperbarui!');
    }

    public function orders()
    {
        $orders = Order::with(['customer', 'vendor', 'bundling', 'payment'])
            ->latest()
            ->paginate(15);

        return view('admin.orders', compact('orders'));
    }

    public function verifyPayment(Request $request, $paymentId)
    {
        $payment = Payment::with('order')->findOrFail($paymentId);

        $validated = $request->validate([
            'action' => ['required', 'in:verify,reject'],
        ]);

        if ($validated['action'] === 'verify') {
            $payment->update([
                'payment_status' => 'verified',
                'verified_at' => now(),
            ]);
            $payment->order->update(['status' => 'paid']);
            $msg = 'Pembayaran untuk pesanan ' . $payment->order->order_code . ' berhasil diverifikasi!';
        } else {
            $payment->update([
                'payment_status' => 'rejected',
                'verified_at' => null,
            ]);
            $payment->order->update(['status' => 'pending']);
            $msg = 'Pembayaran untuk pesanan ' . $payment->order->order_code . ' telah ditolak.';
        }

        return back()->with('success', $msg);
    }

    public function spkMarcos()
    {
        $marcosData = $this->marcosService->calculateRanking();
        return view('admin.spk-marcos', compact('marcosData'));
    }

    public function downloadExcelTemplate()
    {
        $criterias = Criteria::orderBy('code')->get();
        $bundlings = Bundling::with(['vendor', 'evaluations'])->get();

        $filename = 'template_evaluasi_marcos_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($bundlings, $criterias) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            $header = ['id_bundling', 'nama_paket', 'vendor', 'harga'];
            foreach ($criterias as $c) {
                $header[] = $c->code . ' (' . $c->name . ')';
            }
            fputcsv($file, $header);

            // Data rows
            foreach ($bundlings as $b) {
                $row = [
                    $b->id,
                    $b->package_name,
                    $b->vendor->vendor_name ?? '-',
                    $b->base_price,
                ];
                foreach ($criterias as $c) {
                    $eval = $b->evaluations->where('criteria_id', $c->id)->first();
                    $row[] = $eval ? $eval->score : 3;
                }
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importExcelEvaluations(Request $request)
    {
        // Support direct JSON scores parsed by SheetJS Excel viewer or uploaded file
        if ($request->has('json_scores') && !empty($request->input('json_scores'))) {
            $scoresData = json_decode($request->input('json_scores'), true);
            if (is_array($scoresData)) {
                $updatedCount = 0;
                foreach ($scoresData as $bundlingId => $criteriaScores) {
                    if (!is_array($criteriaScores)) continue;
                    foreach ($criteriaScores as $criteriaId => $score) {
                        BundlingEvaluation::updateOrCreate(
                            [
                                'bundling_id' => $bundlingId,
                                'criteria_id' => $criteriaId,
                            ],
                            [
                                'score' => (int) $score,
                            ]
                        );
                        $updatedCount++;
                    }
                }
                return back()->with('success', "Berhasil mengimpor {$updatedCount} nilai evaluasi dari Excel dan memperbarui kalkulasi MARCOS!");
            }
        }

        // Support uploaded CSV/Excel file
        $request->validate([
            'excel_file' => ['required', 'file', 'max:5120'],
        ]);

        $file = $request->file('excel_file');
        $path = $file->getRealPath();

        $criterias = Criteria::orderBy('code')->get();
        $bundlings = Bundling::all();

        $criteriaMap = [];
        foreach ($criterias as $c) {
            $criteriaMap[strtoupper($c->code)] = $c->id;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($lines)) {
            return back()->with('error', 'File yang diunggah kosong atau tidak dapat dibaca.');
        }

        // Parse header
        $headerLine = array_shift($lines);
        // Remove BOM if present
        $headerLine = preg_replace('/^\xEF\xBB\xBF/', '', $headerLine);
        $headers = str_getcsv($headerLine);

        // Map column indices to criteria IDs
        $colToCriteria = [];
        $idColIndex = 0;
        foreach ($headers as $idx => $hName) {
            $hClean = trim($hName);
            if (stripos($hClean, 'id_bundling') !== false || stripos($hClean, 'bundling_id') !== false || strtolower($hClean) === 'id') {
                $idColIndex = $idx;
                continue;
            }
            foreach ($criteriaMap as $code => $critId) {
                if (stripos($hClean, $code) !== false) {
                    $colToCriteria[$idx] = $critId;
                    break;
                }
            }
        }

        $updatedCount = 0;
        foreach ($lines as $line) {
            $row = str_getcsv($line);
            if (empty($row) || !isset($row[$idColIndex])) continue;

            $bundlingIdentifier = trim($row[$idColIndex]);
            $bundling = null;
            if (is_numeric($bundlingIdentifier)) {
                $bundling = $bundlings->firstWhere('id', (int) $bundlingIdentifier);
            }
            if (!$bundling && isset($row[1])) {
                $bundling = $bundlings->firstWhere('package_name', trim($row[1]));
            }

            if (!$bundling) continue;

            foreach ($colToCriteria as $colIdx => $criteriaId) {
                if (isset($row[$colIdx])) {
                    $scoreVal = (int) trim($row[$colIdx]);
                    if ($scoreVal > 0) {
                        BundlingEvaluation::updateOrCreate(
                            [
                                'bundling_id' => $bundling->id,
                                'criteria_id' => $criteriaId,
                            ],
                            [
                                'score' => $scoreVal,
                            ]
                        );
                        $updatedCount++;
                    }
                }
            }
        }

        return back()->with('success', "File Excel berhasil diproses! {$updatedCount} nilai skor evaluasi telah diperbarui dan kalkulasi MARCOS otomatis disinkronkan.");
    }

    public function exportMarcosExcel()
    {
        $marcosData = $this->marcosService->calculateRanking();
        $criterias = Criteria::orderBy('code')->get();

        $filename = 'hasil_kalkulasi_marcos_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($marcosData, $criterias) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Section 1: Ringkasan Ranking
            fputcsv($file, ['HASIL PERANGKINGAN METODE SPK MARCOS']);
            fputcsv($file, ['Peringkat', 'Nama Paket', 'Vendor', 'Harga', 'Total Terbobot (Si)', 'Utilitas Anti-Ideal (K-)', 'Utilitas Ideal (K+)', 'Skor Akhir Kompromi f(Ki)']);
            foreach ($marcosData['rankings'] ?? [] as $r) {
                fputcsv($file, [
                    $r['rank'],
                    $r['package_name'],
                    $r['vendor_name'],
                    $r['price'],
                    round($r['S_i'], 4),
                    round($r['K_minus'], 4),
                    round($r['K_plus'], 4),
                    round($r['final_score'], 5),
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['MATRIKS KEPUTUSAN AWAL (X)']);
            $xHeader = ['Alternatif', 'Vendor'];
            foreach ($criterias as $c) {
                $xHeader[] = $c->code . ' (' . $c->type . ')';
            }
            fputcsv($file, $xHeader);

            foreach ($marcosData['bundlings'] ?? [] as $b) {
                $xRow = [$b->package_name, $b->vendor->vendor_name ?? '-'];
                foreach ($criterias as $c) {
                    $xRow[] = $marcosData['matrix'][$b->id][$c->id] ?? 0;
                }
                fputcsv($file, $xRow);
            }

            // Ideal Solutions
            $aiRow = ['Solusi Ideal (A+)', 'MAX/MIN'];
            $aaiRow = ['Solusi Anti-Ideal (A-)', 'MIN/MAX'];
            foreach ($criterias as $c) {
                $aiRow[] = $marcosData['ideal'][$c->id] ?? 0;
                $aaiRow[] = $marcosData['anti_ideal'][$c->id] ?? 0;
            }
            fputcsv($file, $aiRow);
            fputcsv($file, $aaiRow);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $order = Order::with('payment')->findOrFail($orderId);
        $action = $request->input('action'); // 'accept' or 'reject'

        if ($action === 'accept') {
            $order->update(['status' => 'paid']);
            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'verified',
                    'verified_at' => now(),
                ]);
            }
            $msg = "Pesanan #{$order->order_code} berhasil diterima dan diverifikasi!";
        } elseif ($action === 'reject') {
            $order->update(['status' => 'cancelled']);
            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'rejected',
                ]);
            }
            $msg = "Pesanan #{$order->order_code} telah ditolak.";
        } else {
            return back()->with('error', 'Aksi tidak valid.');
        }

        return back()->with('success', $msg);
    }

    public function printMarcosPdf()
    {
        $marcosData = $this->marcosService->calculateRanking();
        return view('admin.spk-marcos-pdf', compact('marcosData'));
    }

    /**
     * User Management: List all users
     */
    public function users(Request $request)
    {
        $query = User::with('vendor');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                    ->orWhere('email', 'LIKE', "%{$s}%")
                    ->orWhere('phone', 'LIKE', "%{$s}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * User Management: Update user details
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:admin,vendor,customer'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update($validated);

        return back()->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri yang sedang aktif digunakan!');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan kembali' : 'dinonaktifkan';
        return back()->with('success', "Akun pengguna {$user->name} berhasil {$statusText}!");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri yang sedang aktif digunakan!');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Akun pengguna {$name} berhasil dihapus permanen dari sistem!");
    }

    /**
     * Vendor Management: List all vendors
     */
    public function vendors(Request $request)
    {
        $query = Vendor::with('user')
            ->withCount(['bundlings', 'serviceItems', 'orders', 'reviews']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('vendor_name', 'LIKE', "%{$s}%")
                    ->orWhere('owner_name', 'LIKE', "%{$s}%")
                    ->orWhere('phone', 'LIKE', "%{$s}%")
                    ->orWhere('location_address', 'LIKE', "%{$s}%");
            });
        }

        $vendors = $query->latest()->paginate(12)->withQueryString();

        return view('admin.vendors', compact('vendors'));
    }

    /**
     * Vendor Management: Update vendor details
     */
    public function updateVendor(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'vendor_name' => ['required', 'string', 'max:100'],
            'owner_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'location_address' => ['required', 'string', 'max:500'],
            'google_maps_url' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $vendor->update($validated);

        return back()->with('success', "Data mitra vendor {$vendor->vendor_name} berhasil diperbarui.");
    }

    /**
     * Vendor Management: Delete vendor
     */
    public function deleteVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        $name = $vendor->vendor_name;
        $vendor->delete();

        return back()->with('success', "Data mitra vendor {$name} berhasil dihapus.");
    }
}
