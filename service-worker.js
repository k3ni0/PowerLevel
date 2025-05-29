self.addEventListener('install', (e) => {
    console.log('SW installed');
    e.waitUntil(self.skipWaiting());
  });
  
  self.addEventListener('activate', (e) => {
    console.log('SW activated');
    return self.clients.claim();
  });
  
  self.addEventListener('fetch', function (event) {
    // Pour l’instant on ne fait rien, mais c’est prêt pour le mode offline
  });
  