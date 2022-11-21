self.addEventListener("install", function (event) {
  event.waitUntil(preLoad());
});

var filesToCache = [
  '/',
  '/offline.html'
];

var preLoad = function () {
  return caches.open("offline").then(function (cache) {
    // caching index and important routes
    return cache.addAll(filesToCache);
  });
};

self.addEventListener("fetch", function (event) {
  if (!(event.request.url.indexOf('http') === 0)) return;

  event.respondWith(checkResponse(event.request).catch(function () {
    return returnFromCache(event.request);
  }));
  event.waitUntil(addToCache(event.request));
});

var checkResponse = function (request) {
  return new Promise(function (fulfill, reject) {
    fetch(request.clone()).then(function (response) {
      if (response.status !== 404) {
        fulfill(response);
      } else {
        reject();
      }
    }, reject);
  });
};

var addToCache = function (request) {
  return caches.open("offline").then(function (cache) {
    return fetch(request.clone()).then(function (response) {
      return cache.put(request, response);
    });
  });
};

var returnFromCache = function (request) {
  return caches.open("offline").then(function (cache) {
    return cache.match(request).then(function (matching) {
      if (!matching) {
        return cache.match("offline.html");
      } else {
        return matching;
      }
    });
  });
};
