const CACHE = 'mdc-static-v4';
const ASSETS = ['/css/app.css', '/js/app.js', '/images/logo.svg', '/images/chapel.svg', '/images/icon-192.png', '/images/icon-512.png', '/offline.html', '/vendor/flatpickr/flatpickr.min.js', '/vendor/flatpickr/es.js', '/vendor/flatpickr/flatpickr.min.css'];
self.addEventListener('install', event => { event.waitUntil(caches.open(CACHE).then(cache => cache.addAll(ASSETS))); self.skipWaiting(); });
self.addEventListener('activate', event => { event.waitUntil(caches.keys().then(keys => Promise.all(keys.filter(key => key.startsWith('mdc-static-') && key !== CACHE).map(key => caches.delete(key))))); self.clients.claim(); });
self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET' || new URL(event.request.url).origin !== self.location.origin) return;
    if (event.request.mode === 'navigate') {
        event.respondWith(fetch(event.request).catch(() => caches.match('/offline.html')));
    } else if (ASSETS.includes(new URL(event.request.url).pathname)) {
        event.respondWith(caches.match(event.request).then(cached => cached || fetch(event.request)));
    }
});
