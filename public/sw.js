/*
 * E-Perpus service worker.
 *
 * Deliberately conservative: it ONLY caches fingerprinted build assets and
 * static images. HTML pages, Livewire traffic and anything non-GET always go
 * to the network, so the app can never serve a stale page or stale Livewire
 * state. Its main job is making the app installable to the home screen.
 */
const CACHE = 'e-perpus-static-v1';

// Paths safe to serve from cache. Vite fingerprints /build/ filenames, so a
// new deploy produces new URLs and never collides with old cached entries.
const CACHEABLE = [/^\/build\//, /^\/images\//, /^\/favicon\.ico$/];

self.addEventListener('install', () => self.skipWaiting());

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) => Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') return;

    const url = new URL(request.url);

    // Same-origin only; never touch R2 covers, fonts or other third parties.
    if (url.origin !== self.location.origin) return;

    // Livewire must always hit the network.
    if (url.pathname.startsWith('/livewire/')) return;

    if (!CACHEABLE.some((re) => re.test(url.pathname))) return;

    event.respondWith(
        caches.match(request).then(
            (hit) =>
                hit ||
                fetch(request).then((response) => {
                    if (response.ok && response.type === 'basic') {
                        const copy = response.clone();
                        caches.open(CACHE).then((cache) => cache.put(request, copy));
                    }
                    return response;
                })
        )
    );
});
