<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Customer: Chat Inbox & Room
     */
    /**
     * Customer: Chat Inbox & Room (Accessible without role restriction / relog)
     */
    public function customerIndex(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $query = ChatConversation::with(['vendor', 'latestMessage', 'order']);
        
        // Admin can monitor all, while customers & vendors see their involved conversations
        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user, $userId) {
                $q->where('customer_id', $userId);
                if ($user->vendor) {
                    $q->orWhere('vendor_id', $user->vendor->id);
                }
            });
        }

        $conversations = $query->orderByDesc('last_message_at')->get();

        $activeConversation = null;
        $activeId = $request->query('c');

        if ($activeId) {
            $activeQuery = ChatConversation::with(['vendor', 'messages.sender', 'order']);
            if (!$user->isAdmin()) {
                $activeQuery->where(function ($q) use ($user, $userId) {
                    $q->where('customer_id', $userId);
                    if ($user->vendor) {
                        $q->orWhere('vendor_id', $user->vendor->id);
                    }
                });
            }
            $activeConversation = $activeQuery->find($activeId);
        }

        if (!$activeConversation && $conversations->isNotEmpty()) {
            $activeConversation = ChatConversation::with(['vendor', 'messages.sender', 'order'])
                ->find($conversations->first()->id);
        }

        // Mark incoming messages as read
        if ($activeConversation) {
            $activeConversation->messages()
                ->where('sender_id', '!=', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('customer.chat', compact('conversations', 'activeConversation'));
    }

    /**
     * Customer: Start or open conversation with a vendor
     */
    public function customerStart(Request $request, $vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $user = Auth::user();
        $userId = $user->id;
        $orderId = $request->query('order_id');

        // Jika vendor membuka halaman tokonya sendiri, alihkan ke dashboard vendor chat
        if ($user->vendor && $user->vendor->id === $vendor->id) {
            return redirect()->route('vendor.chat')->with('info', 'Anda diarahkan ke ruang chat vendor Anda.');
        }

        $conversation = ChatConversation::firstOrCreate(
            [
                'customer_id' => $userId,
                'vendor_id' => $vendor->id,
            ],
            [
                'order_id' => $orderId,
                'last_message_at' => now(),
            ]
        );

        if ($orderId && !$conversation->order_id) {
            $conversation->update(['order_id' => $orderId]);
        }

        return redirect()->route('customer.chat', ['c' => $conversation->id]);
    }

    /**
     * Customer: Send message asynchronously without reload
     */
    public function customerSendMessage(Request $request, $conversationId)
    {
        $user = Auth::user();
        $userId = $user->id;

        $conversation = ChatConversation::where(function ($q) use ($user, $userId) {
            $q->where('customer_id', $userId);
            if ($user->isAdmin()) {
                $q->orWhereNotNull('id');
            }
            if ($user->vendor) {
                $q->orWhere('vendor_id', $user->vendor->id);
            }
        })->findOrFail($conversationId);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $senderRole = ($user->vendor && $user->vendor->id === $conversation->vendor_id)
            ? 'vendor'
            : ($user->isAdmin() ? 'admin' : 'customer');

        $chatMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'sender_role' => $senderRole,
            'message' => trim($validated['message']),
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $chatMessage->id,
                    'message' => $chatMessage->message,
                    'sender_id' => $chatMessage->sender_id,
                    'sender_role' => $chatMessage->sender_role,
                    'time' => $chatMessage->created_at->format('H:i'),
                    'is_read' => false,
                    'status_text' => 'Terkirim',
                ],
            ]);
        }

        return redirect()->route('customer.chat', ['c' => $conversation->id]);
    }

    /**
     * Customer: Polling new messages without page reload
     */
    public function customerGetMessages(Request $request, $conversationId)
    {
        $user = Auth::user();
        $userId = $user->id;

        $conversation = ChatConversation::where(function ($q) use ($user, $userId) {
            $q->where('customer_id', $userId);
            if ($user->isAdmin()) {
                $q->orWhereNotNull('id');
            }
            if ($user->vendor) {
                $q->orWhere('vendor_id', $user->vendor->id);
            }
        })->findOrFail($conversationId);

        $afterId = (int) $request->query('after_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $afterId)
            ->orderBy('id', 'asc')
            ->get();

        if ($messages->isNotEmpty()) {
            $conversation->messages()
                ->where('sender_id', '!=', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($msg) use ($userId) {
                $isMe = $msg->sender_id === $userId;
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_me' => $isMe,
                    'sender_role' => $msg->sender_role,
                    'time' => $msg->created_at->format('H:i'),
                    'is_read' => $msg->is_read,
                    'status_text' => $msg->is_read ? 'Dibaca' : 'Terkirim',
                ];
            }),
        ]);
    }

    /**
     * Vendor: Chat Inbox & Room
     */
    public function vendorIndex(Request $request)
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Profil vendor belum ditemukan.');
        }

        $conversations = ChatConversation::with(['customer', 'latestMessage', 'order'])
            ->where('vendor_id', $vendor->id)
            ->orderByDesc('last_message_at')
            ->get();

        $activeConversation = null;
        $activeId = $request->query('c');

        if ($activeId) {
            $activeConversation = ChatConversation::with(['customer', 'messages.sender', 'order'])
                ->where('vendor_id', $vendor->id)
                ->find($activeId);
        }

        if (!$activeConversation && $conversations->isNotEmpty()) {
            $activeConversation = ChatConversation::with(['customer', 'messages.sender', 'order'])
                ->find($conversations->first()->id);
        }

        // Mark incoming messages as read
        if ($activeConversation) {
            $activeConversation->messages()
                ->where('sender_id', '!=', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('vendor.chat', compact('conversations', 'activeConversation', 'vendor'));
    }

    /**
     * Vendor: Send message
     */
    public function vendorSendMessage(Request $request, $conversationId)
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor && Auth::user()->isAdmin()) {
            $conversation = ChatConversation::findOrFail($conversationId);
        } else {
            if (!$vendor) {
                return response()->json(['success' => false, 'error' => 'Vendor profile tidak ditemukan.'], 403);
            }
            $conversation = ChatConversation::where('vendor_id', $vendor->id)->findOrFail($conversationId);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $chatMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'sender_role' => 'vendor',
            'message' => trim($validated['message']),
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $chatMessage->id,
                    'message' => $chatMessage->message,
                    'sender_id' => $chatMessage->sender_id,
                    'sender_role' => $chatMessage->sender_role,
                    'time' => $chatMessage->created_at->format('H:i'),
                    'is_read' => false,
                    'status_text' => 'Terkirim',
                ],
            ]);
        }

        return redirect()->route('vendor.chat', ['c' => $conversation->id]);
    }

    /**
     * Vendor: Polling new messages without page reload
     */
    public function vendorGetMessages(Request $request, $conversationId)
    {
        $userId = Auth::id();
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return response()->json(['success' => false, 'messages' => []], 403);
        }

        $conversation = ChatConversation::where('vendor_id', $vendor->id)->findOrFail($conversationId);
        $afterId = (int) $request->query('after_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $afterId)
            ->orderBy('id', 'asc')
            ->get();

        if ($messages->isNotEmpty()) {
            $conversation->messages()
                ->where('sender_id', '!=', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($msg) use ($userId) {
                $isMe = $msg->sender_id === $userId;
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_me' => $isMe,
                    'sender_role' => $msg->sender_role,
                    'time' => $msg->created_at->format('H:i'),
                    'is_read' => $msg->is_read,
                    'status_text' => $msg->is_read ? 'Dibaca Pelanggan' : 'Terkirim',
                ];
            }),
        ]);
    }

    /**
     * Admin: Chat Monitoring Dashboard (Requirement 3)
     */
    public function adminIndex(Request $request)
    {
        $query = ChatConversation::with(['customer', 'vendor', 'latestMessage', 'order'])
            ->withCount('messages');

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->whereHas('customer', function ($sub) use ($s) {
                    $sub->where('name', 'LIKE', "%{$s}%")->orWhere('email', 'LIKE', "%{$s}%");
                })->orWhereHas('vendor', function ($sub) use ($s) {
                    $sub->where('vendor_name', 'LIKE', "%{$s}%");
                });
            });
        }

        $conversations = $query->orderByDesc('last_message_at')->paginate(15);

        return view('admin.chat-monitoring', compact('conversations'));
    }

    /**
     * Admin: View conversation detail transcript
     */
    public function adminView($id)
    {
        $conversation = ChatConversation::with(['customer', 'vendor', 'messages.sender', 'order.items'])
            ->findOrFail($id);

        return view('admin.chat-detail', compact('conversation'));
    }
}
