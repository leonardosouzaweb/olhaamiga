const CACHE_NAME = "olha-amiga-cache-v1";
const urlsToCache = [
    "/olhaamiga/",
    "/olhaamiga/assets/css/style.css",
    "/olhaamiga/assets/js/main.js",
    "/olhaamiga/assets/images/logo.png",
    "/olhaamiga/assets/icons/icon-192x192.png",
    "/olhaamiga/assets/icons/icon-192x192-maskable.png",
    "/olhaamiga/assets/icons/icon-512x512.png",
    "/olhaamiga/assets/icons/icon-512x512-maskable.png",
    "/olhaamiga/assets/icons/icon-144x144.png",
    "/olhaamiga/assets/icons/screenshot1.png",
    "/olhaamiga/assets/icons/screenshot1.png"
];

// Instalação do Service Worker (cache inicial)
self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log("Cache armazenado!");
            return cache.addAll(urlsToCache);
        }).catch(error => {
            console.error("Erro ao armazenar no cache:", error);
        })
    );
});

// Ativação e limpeza de caches antigos
self.addEventListener("activate", event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        console.log("Cache antigo removido:", cache);
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});

// Intercepta requisições e serve do cache primeiro
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        }).catch(error => {
            console.error("Erro ao buscar do cache:", error);
        })
    );
});
