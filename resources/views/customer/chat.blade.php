@extends('layouts.app')

@section('title', 'Pesan & Diskusi - E-Marketplace')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 72px;">
    <!-- Page Header -->
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <span class="badge badge-moss" style="margin-bottom: 6px;">Komunikasi Vendor</span>
            <h1 class="headline-lg">Pesan & Diskusi</h1>
            <p class="body-sm">Diskusikan kustomisasi paket riasan, jadwal fitting busana, dan konsep dekorasi langsung dengan mitra vendor.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="openNewChatModal()" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; box-shadow: var(--shadow-tier1);">
            <i data-lucide="message-square-plus" style="width: 16px; height: 16px;"></i>
            <span>Mulai Chat Vendor Baru</span>
        </button>
    </div>

    @if($conversations->isEmpty() && !$activeConversation)
        <div class="card-kabut" style="text-align: center; padding: 56px 24px; max-width: 680px; margin: 0 auto; border-radius: 16px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--blush-light); color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i data-lucide="message-square" style="width: 32px; height: 32px;"></i>
            </div>
            <h2 class="headline-md" style="font-size: 1.4rem; margin-bottom: 10px;">Mulai Diskusi dengan Vendor Pilihan Anda</h2>
            <p class="body-sm" style="margin-bottom: 24px; color: var(--muted); max-width: 500px; margin-left: auto; margin-right: auto;">
                Pilih salah satu mitra vendor terpercaya kami di bawah ini untuk langsung membuka ruang chat dan mengirimkan pesan kustomisasi Anda:
            </p>
            
            <div style="display: flex; flex-direction: column; gap: 10px; max-width: 480px; margin: 0 auto 28px; text-align: left;">
                @foreach($vendors ?? [] as $v)
                    <a href="{{ route('customer.chat.start', $v->id) }}" 
                       style="display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; border: 1px solid var(--line); border-radius: 12px; text-decoration: none; background: #fff; transition: all 0.2s ease;"
                       onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.borderColor='var(--line)'; this.style.transform='translateY(0)'">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--blush-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                <i data-lucide="store" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <strong style="font-size: 0.95rem; color: var(--ink); display: block;">{{ $v->vendor_name }}</strong>
                                <span class="caption" style="color: var(--muted); font-size: 11.5px;">{{ $v->location_address }}</span>
                            </div>
                        </div>
                        <span class="btn btn-sm btn-primary" style="font-size: 12px; padding: 4px 12px; border-radius: 999px;">
                            Chat Sekarang &rarr;
                        </span>
                    </a>
                @endforeach
            </div>

            <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('vendors') }}" class="btn btn-secondary btn-sm">
                    <i data-lucide="store" style="width: 14px; height: 14px;"></i>
                    Direktori Vendor
                </a>
                <a href="{{ route('bundlings') }}" class="btn btn-secondary btn-sm">
                    <i data-lucide="package" style="width: 14px; height: 14px;"></i>
                    Paket Bundling
                </a>
            </div>
        </div>
    @else
        <!-- Main Chat Container -->
        <div class="card-kabut chat-card-container" style="padding: 0; border-radius: 16px; overflow: hidden; display: grid; grid-template-columns: 340px 1fr; height: 680px; max-height: calc(100vh - 180px); min-height: 560px; box-shadow: var(--shadow-tier1);">
            
            <!-- Sidebar: Conversations List -->
            <div class="chat-sidebar-pane" style="border-right: 1px solid var(--line); display: flex; flex-direction: column; background: var(--surface-low); min-height: 0; height: 100%; overflow: hidden;">
                <!-- Sidebar Header -->
                <div style="padding: 16px 20px; border-bottom: 1px solid var(--line); font-weight: 700; font-size: 0.95rem; color: var(--ink); display: flex; justify-content: space-between; align-items: center; background: #fff; flex-shrink: 0;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="inbox" style="width: 18px; height: 18px; color: var(--primary);"></i>
                        <span>Kotak Masuk</span>
                        <span class="badge badge-moss" style="font-size: 10px; margin-left: 2px;">{{ $conversations->count() }}</span>
                    </div>
                    <button type="button" onclick="openNewChatModal()" class="btn btn-sm btn-secondary" style="font-size: 11.5px; padding: 4px 10px; height: 28px; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px;" title="Mulai chat dengan vendor lain">
                        <i data-lucide="plus" style="width: 12px; height: 12px;"></i>
                        <span>Chat Baru</span>
                    </button>
                </div>

                <!-- Conversations Scrollable Area -->
                <div style="overflow-y: auto; flex: 1 1 0%; min-height: 0;">
                    @forelse($conversations as $c)
                        @php
                            $isActive = $activeConversation && $activeConversation->id === $c->id;
                            $unread = $c->unreadCountForUser(auth()->id());
                        @endphp
                        <a href="{{ route('customer.chat', ['c' => $c->id]) }}" 
                           class="conv-item-link"
                           style="display: block; padding: 14px 18px; border-bottom: 1px solid var(--line); text-decoration: none; background: {{ $isActive ? '#FFFFFF' : 'transparent' }}; border-left: 4px solid {{ $isActive ? 'var(--primary)' : 'transparent' }}; transition: background 0.15s ease;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                                <strong style="font-size: 0.92rem; color: var(--ink); display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px;">{{ $c->vendor->vendor_name }}</strong>
                                <span id="conv-time-{{ $c->id }}" class="caption" style="font-size: 10.5px; color: var(--muted); flex-shrink: 0;">
                                    {{ $c->last_message_at ? $c->last_message_at->format('H:i') : '' }}
                                </span>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <p id="conv-preview-{{ $c->id }}" class="caption" style="margin: 0; color: var(--body); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 210px;">
                                    @if($c->latestMessage)
                                        {{ Str::limit($c->latestMessage->message, 34) }}
                                    @else
                                        <em style="color: var(--muted);">Mulai percakapan...</em>
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
                    @empty
                        <div style="padding: 32px 20px; text-align: center; color: var(--muted); font-size: 13px;">
                            <i data-lucide="message-square" style="width: 28px; height: 28px; margin: 0 auto 8px; opacity: 0.4;"></i>
                            <div>Belum ada obrolan aktif.</div>
                            <button type="button" onclick="openNewChatModal()" class="btn btn-primary btn-sm" style="margin-top: 12px; font-size: 12px;">
                                Pilih Vendor
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Chat Room Area -->
            <div class="chat-room-pane" style="display: flex; flex-direction: column; background: #fff; height: 100%; min-height: 0; overflow: hidden; position: relative;">
                @if($activeConversation)
                    <!-- Chat Header -->
                    <div style="flex-shrink: 0; padding: 14px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: #fff; z-index: 5;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--blush-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">
                                <i data-lucide="store" style="width: 20px; height: 20px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.05rem; margin: 0; color: var(--ink); font-weight: 700;">{{ $activeConversation->vendor->vendor_name }}</h3>
                                <div class="caption" style="color: var(--muted); font-size: 11.5px;">
                                    Kontak: {{ $activeConversation->vendor->phone }} &bull; {{ Str::limit($activeConversation->vendor->location_address, 45) }}
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 8px; flex-shrink: 0;">
                            <a href="{{ route('vendor.detail', $activeConversation->vendor_id) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 12px; height: 32px; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                                <span>Profil</span>
                            </a>
                            @if($activeConversation->vendor->whatsapp_url)
                                <a href="{{ $activeConversation->vendor->whatsapp_url }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 12px; height: 32px; color: #16A34A; display: inline-flex; align-items: center; gap: 4px;">
                                    <i data-lucide="phone" style="width: 12px; height: 12px;"></i>
                                    <span>WhatsApp</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Linked Order Banner if present -->
                    @if($activeConversation->order)
                        <div style="flex-shrink: 0; background: var(--surface-low); padding: 8px 24px; border-bottom: 1px solid var(--line); font-size: 12px; display: flex; justify-content: space-between; align-items: center;">
                            <span>Diskusi Terkait Pesanan: <strong>#{{ $activeConversation->order->order_code }}</strong> (Status: {{ $activeConversation->order->status_label }})</span>
                            <a href="{{ route('customer.order.detail', $activeConversation->order->id) }}" style="font-weight: 600; color: var(--primary); text-decoration: none;">Lihat Detail Pesanan &rarr;</a>
                        </div>
                    @endif

                    <!-- Message Feed (Takes remaining space and scrolls smoothly) -->
                    <div id="messageFeed" style="flex: 1 1 0%; min-height: 0; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 14px; background: #FAF9F6;">
                        @if($activeConversation->messages->isEmpty())
                            <div id="emptyFeedNotice" style="text-align: center; color: var(--muted); margin: auto; font-size: 0.9rem; max-width: 440px; padding: 24px;">
                                <div style="width: 52px; height: 52px; border-radius: 50%; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: var(--primary);">
                                    <i data-lucide="messages-square" style="width: 26px; height: 26px;"></i>
                                </div>
                                <strong style="color: var(--ink); display: block; margin-bottom: 6px;">Mulai Diskusi dengan {{ $activeConversation->vendor->vendor_name }}</strong>
                                <p style="margin: 0; font-size: 13px; line-height: 1.5;">Ketik pesan pertama Anda pada kolom di bawah ini untuk menanyakan paket riasan, jadwal fitting busana, atau kustomisasi dekorasi.</p>
                            </div>
                        @else
                            @foreach($activeConversation->messages as $msg)
                                @php
                                    $isMe = $msg->sender_id === auth()->id();
                                @endphp
                                <div class="chat-bubble-row" data-id="{{ $msg->id }}" style="display: flex; flex-direction: column; align-items: {{ $isMe ? 'flex-end' : 'flex-start' }};">
                                    <div style="max-width: 75%; padding: 12px 18px; border-radius: {{ $isMe ? '16px 16px 2px 16px' : '16px 16px 16px 2px' }}; background: {{ $isMe ? 'var(--primary)' : '#FFFFFF' }}; color: {{ $isMe ? '#FFFFFF' : 'var(--ink)' }}; box-shadow: 0 1px 3px rgba(0,0,0,0.06); font-size: 0.92rem; line-height: 1.5; word-break: break-word;">
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

                    <!-- Chat Input Box (Fixed at bottom of chat room, flex-shrink 0, NEVER HIDDEN) -->
                    <div class="chat-input-wrapper" style="flex-shrink: 0; padding: 14px 20px; border-top: 1px solid var(--line); background: #ffffff; z-index: 10; box-shadow: 0 -3px 12px rgba(0,0,0,0.03);">
                        <form id="customerChatForm" action="{{ route('customer.chat.send', $activeConversation->id) }}" method="POST" style="display: flex; gap: 10px; margin: 0; align-items: center; width: 100%;">
                            @csrf
                            <div style="flex: 1; min-width: 0; position: relative;">
                                <input type="text" 
                                       id="customerChatInput" 
                                       name="message" 
                                       class="form-input" 
                                       placeholder="Ketik pesan Anda untuk vendor di sini... (Tekan Enter untuk kirim)" 
                                       autocomplete="off" 
                                       required 
                                       style="width: 100%; height: 46px; border-radius: 999px; padding: 0 20px; font-size: 13.5px; border: 1.5px solid #E5E7EB; background: #FAFAFA; transition: border-color 0.2s, background 0.2s;">
                            </div>
                            <button type="submit" 
                                    id="customerChatSendBtn" 
                                    class="btn btn-primary" 
                                    style="flex-shrink: 0; height: 46px; border-radius: 999px; padding: 0 22px; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; font-size: 13.5px; cursor: pointer;">
                                <i data-lucide="send" style="width: 15px; height: 15px;"></i>
                                <span>Kirim</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div style="margin: auto; text-align: center; padding: 48px 24px; color: var(--muted); max-width: 400px;">
                        <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--blush-light); color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                            <i data-lucide="message-square" style="width: 30px; height: 30px;"></i>
                        </div>
                        <h3 style="color: var(--ink); font-size: 1.15rem; margin-bottom: 8px;">Pilih Percakapan</h3>
                        <p style="font-size: 13px; line-height: 1.5; margin-bottom: 20px;">Pilih salah satu obrolan dari daftar sebelah kiri atau mulai diskusi baru dengan mitra vendor kami.</p>
                        <button type="button" onclick="openNewChatModal()" class="btn btn-primary btn-sm">
                            Mulai Chat Vendor Baru
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Modal: Pilih Vendor untuk Memulai Chat Baru -->
<div id="newChatModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.45); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; border-radius: 18px; max-width: 520px; width: 100%; max-height: 85vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.18); animation: fadeInChat 0.2s ease-out;">
        <!-- Modal Header -->
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: var(--surface-low);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--blush-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="store" style="width: 18px; height: 18px;"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; color: var(--ink);">Mulai Chat dengan Vendor</h3>
                    <div class="caption" style="color: var(--muted); font-size: 11.5px;">Pilih mitra vendor untuk memulai obrolan</div>
                </div>
            </div>
            <button type="button" onclick="closeNewChatModal()" style="border: none; background: transparent; cursor: pointer; color: var(--muted); padding: 4px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <!-- Modal Body / Vendor List -->
        <div style="padding: 16px 20px; overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 10px;">
            @foreach($vendors ?? [] as $v)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border: 1px solid var(--line); border-radius: 12px; background: #fff; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--surface-low); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">
                            <i data-lucide="store" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div style="min-width: 0;">
                            <strong style="font-size: 0.95rem; color: var(--ink); display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $v->vendor_name }}</strong>
                            <div class="caption" style="color: var(--muted); font-size: 11.5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $v->location_address }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('customer.chat.start', $v->id) }}" class="btn btn-primary btn-sm" style="flex-shrink: 0; font-size: 12px; padding: 6px 14px; border-radius: 999px;">
                        Mulai Chat
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Modal Footer -->
        <div style="padding: 14px 20px; border-top: 1px solid var(--line); text-align: right; background: var(--surface-low);">
            <button type="button" onclick="closeNewChatModal()" class="btn btn-secondary btn-sm" style="padding: 6px 18px;">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openNewChatModal() {
        const modal = document.getElementById('newChatModal');
        if (modal) {
            modal.style.display = 'flex';
            if (window.lucide) window.lucide.createIcons();
        }
    }

    function closeNewChatModal() {
        const modal = document.getElementById('newChatModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Close modal on escape or background click
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeNewChatModal();
    });
    document.getElementById('newChatModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeNewChatModal();
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) window.lucide.createIcons();

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
                <div style="max-width: 75%; padding: 12px 18px; border-radius: ${borderRadius}; background: ${bubbleBg}; color: ${bubbleColor}; box-shadow: 0 1px 3px rgba(0,0,0,0.06); font-size: 0.92rem; line-height: 1.5; word-break: break-word;">
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
                    const text = msg.message.length > 34 ? msg.message.substring(0, 34) + '...' : msg.message;
                    previewEl.textContent = text;
                }
                if (timeEl) {
                    timeEl.textContent = msg.time;
                }
            }
        }

        // AJAX Send Message (Without Page Reload)
        if (form && input) {
            input.focus();

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
    #customerChatInput:focus {
        border-color: var(--primary) !important;
        background: #FFFFFF !important;
        box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.2) !important;
    }
    .conv-item-link:hover {
        background: #F3F4F6 !important;
    }
    @media (max-width: 768px) {
        .chat-card-container {
            grid-template-columns: 1fr !important;
            height: auto !important;
            max-height: none !important;
        }
        .chat-sidebar-pane {
            max-height: 220px !important;
            border-right: none !important;
            border-bottom: 2px solid var(--line) !important;
        }
        .chat-room-pane {
            height: 520px !important;
        }
    }
</style>
@endpush
@endsection
