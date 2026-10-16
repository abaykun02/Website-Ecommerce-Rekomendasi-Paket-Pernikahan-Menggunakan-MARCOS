@extends('layouts.app')

@section('title', 'Pesan & Diskusi - E-Marketplace')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 72px;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px;">
        <span class="badge badge-moss" style="margin-bottom: 6px;">Komunikasi Vendor</span>
        <h1 class="headline-lg">Pesan & Diskusi</h1>
        <p class="body-sm">Diskusikan kustomisasi paket riasan, jadwal fitting busana, dan konsep dekorasi langsung dengan mitra vendor.</p>
    </div>

    @if($conversations->isEmpty())
        <div class="card-kabut" style="text-align: center; padding: 64px 24px; max-width: 600px; margin: 0 auto; border-radius: 16px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--blush-light); color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i data-lucide="message-square" style="width: 32px; height: 32px;"></i>
            </div>
            <h2 class="headline-md" style="font-size: 1.4rem; margin-bottom: 10px;">Belum Ada Percakapan Aktif</h2>
            <p class="body-sm" style="margin-bottom: 24px; color: var(--muted);">
                Anda dapat memulai obrolan dengan vendor melalui halaman profil vendor atau halaman detail paket yang ingin Anda pesan.
            </p>
            <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('vendors') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="store" style="width: 14px; height: 14px;"></i>
                    Cari Direktori Vendor
                </a>
                <a href="{{ route('bundlings') }}" class="btn btn-secondary btn-sm">
                    <i data-lucide="package" style="width: 14px; height: 14px;"></i>
                    Lihat Paket Bundling
                </a>
            </div>
        </div>
    @else
        <div class="card-kabut" style="padding: 0; border-radius: 16px; overflow: hidden; display: grid; grid-template-columns: 320px 1fr; min-height: 600px; max-height: 720px; box-shadow: var(--shadow-tier1);">
            <!-- Sidebar: Conversations List -->
            <div style="border-right: 1px solid var(--line); display: flex; flex-direction: column; background: var(--surface-low);">
                <div style="padding: 18px 20px; border-bottom: 1px solid var(--line); font-weight: 700; font-size: 1rem; color: var(--ink); display: flex; justify-content: space-between; align-items: center;">
                    <span>Kotak Masuk Diskusi</span>
                    <span class="badge badge-moss" style="font-size: 10px;">{{ $conversations->count() }} Percakapan</span>
                </div>

                <div style="overflow-y: auto; flex: 1;">
                    @foreach($conversations as $c)
                        @php
                            $isActive = $activeConversation && $activeConversation->id === $c->id;
                            $unread = $c->unreadCountForUser(auth()->id());
                        @endphp
                        <a href="{{ route('customer.chat', ['c' => $c->id]) }}" 
                           style="display: block; padding: 16px 18px; border-bottom: 1px solid var(--line); text-decoration: none; background: {{ $isActive ? '#FFFFFF' : 'transparent' }}; border-left: 4px solid {{ $isActive ? 'var(--primary)' : 'transparent' }}; transition: background 0.15s ease;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                                <strong style="font-size: 0.95rem; color: var(--ink);">{{ $c->vendor->vendor_name }}</strong>
                                <span id="conv-time-{{ $c->id }}" class="caption" style="font-size: 10.5px; color: var(--muted);">
                                    {{ $c->last_message_at ? $c->last_message_at->format('H:i') : '' }}
                                </span>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <p id="conv-preview-{{ $c->id }}" class="caption" style="margin: 0; color: var(--body); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                    @if($c->latestMessage)
                                        {{ Str::limit($c->latestMessage->message, 36) }}
                                    @else
                                        <em>Mulai percakapan...</em>
                                    @endif
                                </p>
                                @if($unread > 0)
                                    <span id="conv-unread-{{ $c->id }}" style="background: var(--primary); color: #fff; font-size: 10px; font-weight: 700; border-radius: 999px; padding: 1px 6px;">
                                        {{ $unread }}
                                    </span>
                                @endif
                            </div>

                            @if($c->order)
                                <div style="margin-top: 6px;">
                                    <span class="badge badge-outline" style="font-size: 9.5px; padding: 1px 6px;">
                                        Pesanan #{{ $c->order->order_code }}
                                    </span>
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Chat Room Area -->
            @if($activeConversation)
                <div style="display: flex; flex-direction: column; background: #fff; height: 100%;">
                    <!-- Chat Header -->
                    <div style="padding: 16px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: #fff;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--blush-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                <i data-lucide="store" style="width: 20px; height: 20px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.05rem; margin: 0; color: var(--ink);">{{ $activeConversation->vendor->vendor_name }}</h3>
                                <div class="caption" style="color: var(--muted);">
                                    Kontak: {{ $activeConversation->vendor->phone }} &bull; {{ $activeConversation->vendor->location_address }}
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('vendor.detail', $activeConversation->vendor_id) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 12px; height: 32px;">
                                <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                                Profil Vendor
                            </a>
                            @if($activeConversation->vendor->whatsapp_url)
                                <a href="{{ $activeConversation->vendor->whatsapp_url }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 12px; height: 32px; color: #16A34A;">
                                    <i data-lucide="phone" style="width: 12px; height: 12px;"></i>
                                    WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Linked Order Banner if present -->
                    @if($activeConversation->order)
                        <div style="background: var(--surface-low); padding: 8px 24px; border-bottom: 1px solid var(--line); font-size: 12px; display: flex; justify-content: space-between; align-items: center;">
                            <span>Diskusi Terkait Pesanan: <strong>#{{ $activeConversation->order->order_code }}</strong> (Status: {{ $activeConversation->order->status_label }})</span>
                            <a href="{{ route('customer.order.detail', $activeConversation->order->id) }}" style="font-weight: 600; color: var(--primary);">Lihat Detail Pesanan &rarr;</a>
                        </div>
                    @endif

                    <!-- Message Feed -->
                    <div id="messageFeed" style="flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 14px; background: #FAF9F6;">
                        @if($activeConversation->messages->isEmpty())
                            <div id="emptyFeedNotice" style="text-align: center; color: var(--muted); margin: auto; font-size: 0.9rem;">
                                <i data-lucide="messages-square" style="width: 32px; height: 32px; margin-bottom: 8px; opacity: 0.4;"></i>
                                <div>Belum ada pesan. Mulai diskusi Anda seputar paket dan jadwal acara dengan vendor!</div>
                            </div>
                        @else
                            @foreach($activeConversation->messages as $msg)
                                @php
                                    $isMe = $msg->sender_id === auth()->id();
                                @endphp
                                <div class="chat-bubble-row" data-id="{{ $msg->id }}" style="display: flex; flex-direction: column; align-items: {{ $isMe ? 'flex-end' : 'flex-start' }};">
                                    <div style="max-width: 70%; padding: 12px 16px; border-radius: {{ $isMe ? '16px 16px 2px 16px' : '16px 16px 16px 2px' }}; background: {{ $isMe ? 'var(--primary)' : '#FFFFFF' }}; color: {{ $isMe ? '#FFFFFF' : 'var(--ink)' }}; box-shadow: 0 1px 3px rgba(0,0,0,0.06); font-size: 0.92rem; line-height: 1.5; word-break: break-word;">
                                        {{ $msg->message }}
                                    </div>
                                    <span class="caption" style="font-size: 10.5px; color: var(--muted); margin-top: 3px; padding: 0 4px;">
                                        {{ $msg->created_at->format('H:i') }}
                                        @if($isMe)
                                            &bull; {{ $msg->is_read ? 'Dibaca' : 'Terkirim' }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Chat Input Box -->
                    <div style="padding: 16px 24px; border-top: 1px solid var(--line); background: #fff;">
                        <form id="customerChatForm" action="{{ route('customer.chat.send', $activeConversation->id) }}" method="POST" style="display: flex; gap: 12px; margin: 0;">
                            @csrf
                            <input type="text" id="customerChatInput" name="message" class="form-input" placeholder="Ketik pesan Anda untuk vendor di sini..." autocomplete="off" required style="height: 44px; border-radius: 999px; padding: 0 20px;">
                            <button type="submit" id="customerChatSendBtn" class="btn btn-primary" style="height: 44px; border-radius: 999px; padding: 0 22px; display: inline-flex; align-items: center; gap: 6px;">
                                <i data-lucide="send" style="width: 15px; height: 15px;"></i>
                                <span>Kirim</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const feed = document.getElementById('messageFeed');
        const form = document.getElementById('customerChatForm');
        const input = document.getElementById('customerChatInput');
        const sendBtn = document.getElementById('customerChatSendBtn');
        const activeConvId = {{ $activeConversation ? $activeConversation->id : 'null' }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        function scrollToBottom(smooth = false) {
            if (!feed) return;
            if (smooth) {
                feed.scrollTo({ top: feed.scrollHeight, behavior: 'smooth' });
            } else {
                feed.scrollTop = feed.scrollHeight;
            }
        }

        scrollToBottom();

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function getHighestMessageId() {
            let maxId = 0;
            document.querySelectorAll('#messageFeed .chat-bubble-row').forEach(el => {
                const id = parseInt(el.getAttribute('data-id'), 10);
                if (id && id > maxId) maxId = id;
            });
            return maxId;
        }

        function appendMessageBubble(msg, isMe = true) {
            // Remove empty notice if present
            const emptyNotice = document.getElementById('emptyFeedNotice');
            if (emptyNotice) {
                emptyNotice.remove();
            }

            // Check if bubble with this id already rendered
            if (msg.id && document.querySelector(`#messageFeed .chat-bubble-row[data-id="${msg.id}"]`)) {
                return;
            }

            const row = document.createElement('div');
            row.className = 'chat-bubble-row';
            if (msg.id) row.setAttribute('data-id', msg.id);
            row.style.display = 'flex';
            row.style.flexDirection = 'column';
            row.style.alignItems = isMe ? 'flex-end' : 'flex-start';
            row.style.animation = 'fadeInChat 0.2s ease-out';

            const bubbleBg = isMe ? 'var(--primary)' : '#FFFFFF';
            const bubbleColor = isMe ? '#FFFFFF' : 'var(--ink)';
            const borderRadius = isMe ? '16px 16px 2px 16px' : '16px 16px 16px 2px';
            const statusSuffix = isMe ? ` &bull; ${msg.status_text || 'Terkirim'}` : '';

            row.innerHTML = `
                <div style="max-width: 70%; padding: 12px 16px; border-radius: ${borderRadius}; background: ${bubbleBg}; color: ${bubbleColor}; box-shadow: 0 1px 3px rgba(0,0,0,0.06); font-size: 0.92rem; line-height: 1.5; word-break: break-word;">
                    ${escapeHtml(msg.message)}
                </div>
                <span class="caption" style="font-size: 10.5px; color: var(--muted); margin-top: 3px; padding: 0 4px;">
                    ${msg.time}${statusSuffix}
                </span>
            `;

            feed.appendChild(row);
            scrollToBottom(true);

            // Update sidebar
            if (activeConvId) {
                const previewEl = document.getElementById(`conv-preview-${activeConvId}`);
                const timeEl = document.getElementById(`conv-time-${activeConvId}`);
                if (previewEl) {
                    const text = msg.message.length > 36 ? msg.message.substring(0, 36) + '...' : msg.message;
                    previewEl.textContent = text;
                }
                if (timeEl) {
                    timeEl.textContent = msg.time;
                }
            }
        }

        // AJAX Send Message (Without Page Reload / Tanpa Perlu Relog)
        if (form && input) {
            // Allow Enter key to send message easily
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const text = input.value.trim();
                if (!text) return;

                // Disable submit button briefly to prevent double submit
                sendBtn.disabled = true;
                const originalBtnHtml = sendBtn.innerHTML;
                sendBtn.innerHTML = `
                    <span class="material-symbols-outlined" style="font-size: 16px; animation: spin 0.8s linear infinite;">progress_activity</span>
                    <span>Mengirim...</span>
                `;

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ message: text })
                    });

                    if (!response.ok) {
                        const errData = await response.json().catch(() => ({}));
                        throw new Error(errData.message || 'Gagal mengirim pesan.');
                    }

                    const data = await response.json();
                    if (data.success && data.message) {
                        appendMessageBubble(data.message, true);
                        input.value = '';
                    } else {
                        alert(data.message || 'Gagal mengirim pesan.');
                    }
                } catch (err) {
                    console.error('Chat send error:', err);
                    alert(err.message || 'Gagal mengirim pesan. Silakan coba kembali.');
                } finally {
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = originalBtnHtml;
                    if (window.lucide) window.lucide.createIcons();
                    input.focus();
                }
            });
        }

        // Background Polling for Incoming Replies every 3.5 seconds
        if (activeConvId && feed) {
            setInterval(async () => {
                const afterId = getHighestMessageId();
                try {
                    const res = await fetch(`{{ url('/pesan-chat/messages') }}/${activeConvId}?after_id=${afterId}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        if (data.success && Array.isArray(data.messages) && data.messages.length > 0) {
                            data.messages.forEach(m => {
                                appendMessageBubble(m, m.is_me);
                            });
                        }
                    }
                } catch (e) {
                    // Silent fail on network blip
                }
            }, 3500);
        }
    });
</script>
<style>
    @keyframes fadeInChat {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush
@endsection
