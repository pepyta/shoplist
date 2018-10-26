if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/list/js/sw.js').then(function (registration) {
            // Registration was successful
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function (err) {
            // registration failed :(
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}

self.addEventListener('install', function (event) {
    var CACHE_NAME = 'shoplist.ml-cache';
    var urlsToCache = [
      '/',
      '/css/index.css',
      '/css/index.min.css',
        '/img/devices.PNG',
        '/img/web_hi_res_512.png',
        '/list/css/index.css',
        '/list/css/global.css',
        '/list/css/mylists.css',
        '/list/css/resetpw.css',
        '/list/css/register.css',
        '/list/css/login.css',
        '/list/css/trash.css',
        '/list/css/account.css',
        '/list/js/open-source-licenses.css',
        '/list/img/blank.png',
        '/list/img/github.png',
        '/list/img/logo.png',
        '/list/img/materializecss.png',
        '/list/img/mit.png',
        '/list/img/web_hi_res_512.png',
        '/list/js/index.js',
        '/list/js/global.js',
        '/list/js/mylists.js',
        '/list/js/resetpw.js',
        '/list/js/register.js',
        '/list/js/login.js',
        '/list/js/trash.js',
        '/list/js/account.js',
        '/list/js/open-source-licenses.js',
        '/list/licenses/JQuery',
        '/list/licenses/Materialize',
        '/list/licenses/Materialize-Stepper',
        '/list/licenses/PHPMailer',
        '/list/index.php',
        '/list/about.php',
        '/list/trash.php',
        '/list/mylists.php',
        '/list/login.php',
        '/list/register.php',
        '/list/open-source-licenses.php',
        '/list/logout.php',
                '/css/index.css',
        '/css/global.css',
        '/css/mylists.css',
        '/css/resetpw.css',
        '/css/register.css',
        '/css/login.css',
        '/css/trash.css',
        '/css/account.css',
        '/js/open-source-licenses.css',
        '/img/blank.png',
        '/img/github.png',
        '/img/logo.png',
        '/img/materializecss.png',
        '/img/mit.png',
        '/img/web_hi_res_512.png',
        '/js/index.js',
        '/js/global.js',
        '/js/mylists.js',
        '/js/resetpw.js',
        '/js/register.js',
        '/js/login.js',
        '/js/trash.js',
        '/js/account.js',
        '/js/open-source-licenses.js',
        '/licenses/JQuery',
        '/licenses/Materialize',
        '/licenses/Materialize-Stepper',
        '/licenses/PHPMailer',
        '/index.php',
        '/about.php',
        '/trash.php',
        '/mylists.php',
        '/login.php',
        '/register.php',
        '/open-source-licenses.php',
        '/logout.php'
    ];

    self.addEventListener('install', function (event) {
        // Perform install steps
        event.waitUntil(
            caches.open(CACHE_NAME)
            .then(function (cache) {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
        );
    });

});


self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request)
        .then(function (response) {
            // Cache hit - return response
            if (response) {
                return response;
            }

            // IMPORTANT: Clone the request. A request is a stream and
            // can only be consumed once. Since we are consuming this
            // once by cache and once by the browser for fetch, we need
            // to clone the response.
            var fetchRequest = event.request.clone();

            return fetch(fetchRequest).then(
                function (response) {
                    // Check if we received a valid response
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }

                    // IMPORTANT: Clone the response. A response is a stream
                    // and because we want the browser to consume the response
                    // as well as the cache consuming the response, we need
                    // to clone it so we have two streams.
                    var responseToCache = response.clone();

                    caches.open(CACHE_NAME)
                        .then(function (cache) {
                            cache.put(event.request, responseToCache);
                        });

                    return response;
                }
            );
        })
    );
});

self.addEventListener('activate', function (event) {

    var cacheWhitelist = ['shoplist.ml-cache'];

    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames.map(function (cacheName) {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
