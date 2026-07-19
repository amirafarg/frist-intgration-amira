'use strict';

const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const Redis = require('ioredis');

// ─── Config ───────────────────────────────────────────────────────────────────
const PORT          = process.env.PORT          || 3000;
const REDIS_HOST    = process.env.REDIS_HOST    || '127.0.0.1';
const REDIS_PORT    = parseInt(process.env.REDIS_PORT || '6379', 10);
const REDIS_PASS    = process.env.REDIS_PASSWORD || null;
const SOCKET_SECRET = process.env.SOCKET_SECRET || '';
const ALLOWED_ORIGIN = process.env.ALLOWED_ORIGIN || 'http://localhost';

if (!SOCKET_SECRET) {
    console.error('[realtime] SOCKET_SECRET is not set — refusing to start.');
    process.exit(1);
}

// ─── Express + HTTP server ────────────────────────────────────────────────────
const app    = express();
const server = http.createServer(app);

// Health-check endpoint (used by Docker depends_on / load-balancers)
app.get('/health', (_req, res) => res.json({ status: 'ok' }));

// ─── Socket.io ────────────────────────────────────────────────────────────────
const io = new Server(server, {
    cors: {
        origin: ALLOWED_ORIGIN,
        methods: ['GET', 'POST'],
        credentials: true,
    },
});

// Auth middleware — validate the shared secret sent by the browser
io.use((socket, next) => {
    const token = socket.handshake.auth?.token;
    if (token === SOCKET_SECRET) {
        return next();
    }
    console.warn('[realtime] Rejected socket connection — bad token.', {
        id: socket.id,
        ip: socket.handshake.address,
    });
    next(new Error('Unauthorized'));
});

io.on('connection', (socket) => {
    // Place every authenticated admin socket into the 'admins' room
    socket.join('admins');
    console.log(`[realtime] Admin connected: ${socket.id}  (admins room size: ${io.sockets.adapter.rooms.get('admins')?.size ?? 0})`);

    socket.on('disconnect', (reason) => {
        console.log(`[realtime] Admin disconnected: ${socket.id}  reason=${reason}`);
    });
});

// ─── Redis subscriber ─────────────────────────────────────────────────────────
const redisOptions = {
    host: REDIS_HOST,
    port: REDIS_PORT,
    password: REDIS_PASS || undefined,
    // Retry forever with a capped back-off — Node.js should keep running
    // even if Redis briefly restarts.
    retryStrategy: (times) => Math.min(times * 100, 3000),
};

// ioredis requires a DEDICATED connection for subscribe mode.
const subscriber = new Redis(redisOptions);

subscriber.on('connect', () => {
    console.log(`[realtime] Redis subscriber connected  (${REDIS_HOST}:${REDIS_PORT})`);
});

subscriber.on('error', (err) => {
    console.error('[realtime] Redis subscriber error:', err.message);
});

// Subscribe to the channel Laravel publishes on
subscriber.subscribe('daftra.customer.created', (err, count) => {
    if (err) {
        console.error('[realtime] Failed to subscribe:', err.message);
        return;
    }
    console.log(`[realtime] Subscribed to ${count} Redis channel(s).`);
});

subscriber.on('message', (channel, message) => {
    if (channel !== 'daftra.customer.created') return;

    let data;
    try {
        data = JSON.parse(message);
    } catch {
        console.error('[realtime] Could not parse Redis message:', message);
        return;
    }

    console.log('[realtime] Broadcasting customer:created →', {
        customer_id: data.customer_id,
        email: data.email,
    });

    // Emit only to authenticated admin browsers
    io.to('admins').emit('customer:created', data);
});

// ─── Start ────────────────────────────────────────────────────────────────────
server.listen(PORT, () => {
    console.log(`[realtime] WebSocket server listening on port ${PORT}`);
});
