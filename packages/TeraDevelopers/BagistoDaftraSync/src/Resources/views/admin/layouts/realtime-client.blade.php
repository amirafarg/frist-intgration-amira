{{--
    Daftra Realtime — Socket.io client injected into the Bagisto Admin layout.

    Listens for 'customer:created' events published by the Node.js WebSocket
    server and shows a toast notification. If the admin is on the Customers
    list page the DataGrid is also reloaded automatically.

    Auth: the shared SOCKET_SECRET is embedded at render time (server-side).
    For production, upgrade to short-lived per-session tokens.
--}}

{{-- Load Socket.io client from the Node.js server itself (no CDN dependency) --}}
<script src="{{ rtrim(config('app.url'), '/') }}:{{ env('REALTIME_PORT', 3000) }}/socket.io/socket.io.js"></script>

<script>
(function () {
    'use strict';

    const SOCKET_URL   = {{ Js::from(rtrim(config('app.url'), '/') . ':' . env('REALTIME_PORT', 3000)) }};
    const SOCKET_TOKEN = {{ Js::from(env('SOCKET_SECRET', '')) }};

    const socket = io(SOCKET_URL, {
        auth: { token: SOCKET_TOKEN },

        // Reconnect automatically with exponential back-off
        reconnection: true,
        reconnectionAttempts: Infinity,
        reconnectionDelay: 1000,
        reconnectionDelayMax: 10000,
    });

    socket.on('connect', () => {
        console.debug('[DaftraSync] WebSocket connected:', socket.id);
    });

    socket.on('connect_error', (err) => {
        console.warn('[DaftraSync] WebSocket connection error:', err.message);
    });

    socket.on('disconnect', (reason) => {
        console.debug('[DaftraSync] WebSocket disconnected:', reason);
    });

    // ── Handle new customer event ──────────────────────────────────────────
    socket.on('customer:created', function (data) {
        console.debug('[DaftraSync] customer:created received', data);

        const name  = (data.first_name || '') + ' ' + (data.last_name || '');
        const email = data.email || '';

        showDaftraToast(name.trim(), email, data.customer_id);

        // If the admin is currently viewing the Customers list, reload the
        // DataGrid so the new row appears without a manual refresh.
        const onCustomerList = window.location.pathname.includes('/customers');
        if (onCustomerList) {
            // Bagisto DataGrids are Vue components. Dispatching a custom event
            // is the cleanest way to trigger a reload without coupling to
            // internal Bagisto APIs.
            setTimeout(() => window.location.reload(), 2500);
        }
    });

    // ── Toast helper ───────────────────────────────────────────────────────
    function showDaftraToast(name, email, customerId) {
        // Remove any existing Daftra toast before adding a new one
        const existing = document.getElementById('daftra-realtime-toast');
        if (existing) existing.remove();

        const adminUrl = {{ Js::from(url(config('app.admin_url', 'admin'))) }};
        const viewUrl  = adminUrl + '/customers/' + customerId + '/edit';

        const toast = document.createElement('div');
        toast.id = 'daftra-realtime-toast';

        Object.assign(toast.style, {
            position:        'fixed',
            bottom:          '24px',
            right:           '24px',
            zIndex:          '99999',
            display:         'flex',
            alignItems:      'flex-start',
            gap:             '12px',
            background:      '#1e293b',
            color:           '#f1f5f9',
            borderRadius:    '12px',
            padding:         '16px 20px',
            boxShadow:       '0 8px 32px rgba(0,0,0,0.35)',
            maxWidth:        '360px',
            fontFamily:      'Poppins, Inter, sans-serif',
            fontSize:        '14px',
            lineHeight:      '1.5',
            transition:      'opacity 0.4s ease',
            opacity:         '0',
            borderLeft:      '4px solid #3b82f6',
        });

        toast.innerHTML = `
            <div style="flex-shrink:0; margin-top:2px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div style="flex:1;">
                <div style="font-weight:600; color:#e2e8f0; margin-bottom:2px;">New Daftra customer</div>
                <div style="color:#94a3b8; font-size:13px;">${escHtml(name)}</div>
                ${email ? '<div style="color:#64748b; font-size:12px;">' + escHtml(email) + '</div>' : ''}
                <a href="${escHtml(viewUrl)}"
                   style="display:inline-block; margin-top:8px; font-size:12px; color:#3b82f6; text-decoration:none; font-weight:500;">
                   View customer →
                </a>
            </div>
            <button
                onclick="document.getElementById('daftra-realtime-toast').remove()"
                style="background:none; border:none; cursor:pointer; color:#475569; padding:0; margin-top:2px; font-size:18px; line-height:1;">
                &times;
            </button>
        `;

        document.body.appendChild(toast);
        // Trigger fade-in on next frame
        requestAnimationFrame(() => { toast.style.opacity = '1'; });

        // Auto-dismiss after 8 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, 8000);
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
</script>
