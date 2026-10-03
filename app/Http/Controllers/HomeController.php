<?php

namespace App\Http\Controllers;

use App\Models\Bundling;
use App\Models\Category;
use App\Models\Criteria;
use App\Models\ServiceItem;
use App\Models\Vendor;
use App\Services\MarcosService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected MarcosService $marcosService;

    public function __construct(MarcosService $marcosService)
    {
        $this->marcosService = $marcosService;
    }

    public function index(Request $request)
    {
        $rawPref = $request->input('preferences', $request->input('preference', []));
        $preferences = is_array($rawPref) ? array_values(array_filter($rawPref)) : ($rawPref ? [$rawPref] : []);
        $marcosData = $this->marcosService->calculateRanking($preferences);
        $topRecommendations = array_slice($marcosData['rankings'] ?? [], 0, 4);

        $bundlings = Bundling::with(['vendor', 'galleries'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::all();

        // Get individual service items across categories
        $activeCategorySlug = $request->input('kategori', 'all');
        $serviceQuery = ServiceItem::with(['vendor', 'category'])->where('is_active', true);
        if ($activeCategorySlug !== 'all') {
            $serviceQuery->whereHas('category', function ($q) use ($activeCategorySlug) {
                $q->where('slug', $activeCategorySlug);
            });
        }
        $services = $serviceQuery->latest()->take(12)->get();

        $makeupServices = ServiceItem::with('vendor')
            ->whereHas('category', fn($q) => $q->where('slug', 'make-up-only'))
            ->where('is_active', true)->take(4)->get();

        $decorServices = ServiceItem::with('vendor')
            ->whereHas('category', fn($q) => $q->where('slug', 'dekorasi-only'))
            ->where('is_active', true)->take(4)->get();

        $akadServices = ServiceItem::with('vendor')
            ->whereHas('category', fn($q) => $q->where('slug', 'dekorasi-akad'))
            ->where('is_active', true)->take(4)->get();

        $gownServices = ServiceItem::with('vendor')
            ->whereHas('category', fn($q) => $q->where('slug', 'gaun-busana'))
            ->where('is_active', true)->take(4)->get();

        $traditionalServices = ServiceItem::with('vendor')
            ->whereHas('category', fn($q) => $q->where('slug', 'baju-adat'))
            ->where('is_active', true)->take(4)->get();

        $vendors = Vendor::with(['reviews', 'galleries'])
            ->withCount('bundlings')
            ->take(6)
            ->get();

        $criterias = Criteria::orderBy('code')->get();

        return view('home', compact(
            'topRecommendations',
            'bundlings',
            'vendors',
            'criterias',
            'categories',
            'services',
            'makeupServices',
            'decorServices',
            'akadServices',
            'gownServices',
            'traditionalServices',
            'activeCategorySlug',
            'preferences',
            'marcosData'
        ));
    }

    public function bundlings(Request $request)
    {
        $query = Bundling::with(['vendor', 'galleries', 'evaluations.criteria'])
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('package_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('vendor_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        $bundlings = $query->paginate(9)->withQueryString();
        $vendors = Vendor::all();

        // Get rank map from MARCOS with active customer multi-preferences
        $rawPref = $request->input('preferences', $request->input('preference', []));
        $preferences = is_array($rawPref) ? array_values(array_filter($rawPref)) : ($rawPref ? [$rawPref] : []);
        $marcosData = $this->marcosService->calculateRanking($preferences);
        $rankMap = [];
        foreach ($marcosData['rankings'] ?? [] as $item) {
            $rankMap[$item['bundling_id']] = [
                'rank' => $item['rank'],
                'final_score' => $item['final_score'],
            ];
        }

        return view('marketplace.bundlings', compact('bundlings', 'vendors', 'rankMap', 'preferences'));
    }

    public function bundlingDetail($id)
    {
        $bundling = Bundling::with(['vendor.reviews.customer', 'vendor.galleries', 'galleries', 'evaluations.criteria'])
            ->findOrFail($id);

        $marcosData = $this->marcosService->calculateRanking();
        $marcosInfo = collect($marcosData['rankings'] ?? [])->firstWhere('bundling_id', $bundling->id);

        $otherPackages = Bundling::where('vendor_id', $bundling->vendor_id)
            ->where('id', '!=', $bundling->id)
            ->where('is_active', true)
            ->take(3)
            ->get();

        return view('marketplace.bundling-detail', compact('bundling', 'marcosInfo', 'otherPackages'));
    }

    public function vendors(Request $request)
    {
        $query = Vendor::with(['reviews', 'galleries', 'bundlings']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('location_address', 'like', "%{$search}%");
            });
        }

        $vendors = $query->paginate(8)->withQueryString();
        return view('marketplace.vendors', compact('vendors'));
    }

    public function vendorDetail($id)
    {
        $vendor = Vendor::with(['bundlings.galleries', 'galleries', 'reviews.customer', 'reviews.order'])
            ->findOrFail($id);

        return view('marketplace.vendor-detail', compact('vendor'));
    }

    public function spkRecommendation(Request $request)
    {
        $preferences = (array) $request->input('preferences', $request->input('preference', []));
        $preferences = array_filter($preferences);
        $marcosData = $this->marcosService->calculateRanking($preferences);
        $criterias = Criteria::orderBy('code')->get();

        return view('marketplace.spk-marcos', compact('marcosData', 'preferences', 'criterias'));
    }

    public function services(Request $request)
    {
        $categories = Category::all();
        $query = ServiceItem::with(['vendor', 'category'])->where('is_active', true);

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('vendor', fn($vq) => $vq->where('vendor_name', 'like', "%{$search}%"));
            });
        }

        $services = $query->latest()->paginate(12)->withQueryString();
        $vendors = Vendor::all();

        return view('marketplace.services', compact('services', 'categories', 'vendors'));
    }

    public function serviceDetail($id)
    {
        $service = ServiceItem::with(['vendor.reviews.customer', 'vendor.galleries', 'category'])
            ->findOrFail($id);

        $otherServices = ServiceItem::where('vendor_id', $service->vendor_id)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->take(3)
            ->get();

        return view('marketplace.service-detail', compact('service', 'otherServices'));
    }
}
