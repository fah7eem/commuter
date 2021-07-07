const version = '032';
const cache_name = 'Commuter';
const assets = [
  '/home',
  "/assets/css/theme.css",
  "/assets/css/bootstrap.min.css",
  "/assets/js/bootstrap.min.js",
  "/assets/js/jquery-3.5.1.js",
  "/assets/js/theme.js"
];

self.addEventListener("install", event => {
  console.log("installing...");
  caches.delete(cache_name);
  event.waitUntil(
      caches
          .open(cache_name)
          .then(cache => {
              return cache.addAll(assets);
          })
          .catch(err => console.log(err))
  );
});


self.addEventListener("fetch", fetchEvent => {
fetchEvent.respondWith(
  caches.match(fetchEvent.request).then(res => {
    if(navigator.onLine === false){
        return res || fetch(fetchEvent.request, {cache: "no-store"});
    }else{
        return fetch(fetchEvent.request, {cache: "no-store"}) ||  res;
    }
  }).catch(function(error) {
      console.warn('Constructing a fallback response, ' +
        'due to an error while fetching the real response:', error);
        
      var fallbackResponse = '<h1>Offline:</h1><h3 class="text-center">No internet connection</h3>';
      return new Response(fallbackResponse, {
        headers: {'Content-Type': 'text/html'}
      });
  })
);
});