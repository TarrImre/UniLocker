self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open('offline-cache').then(function(cache) {
            return cache.addAll([
                '/',
                '/index.html',
                '/offline.html'
            ]);
        })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        fetch(event.request).catch(function() {
            return caches.match(event.request).then(function(response) {
                return response || caches.match('/offline.html');
            });
        })
    );
});
