const CACHE_NAME = 'shoe-ar-cache-v1';
const URLS_TO_CACHE = [
    '/',
    '/add.php',
    '/list.php',
    '/navbar.php',
    '/index.css',
    '/tailwind.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
];

// --- IndexedDB Helper ---
function openDb() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('offline-shoes-db', 1);
        request.onupgradeneeded = event => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('shoes_queue')) {
                db.createObjectStore('shoes_queue', { autoIncrement: true });
            }
        };
        request.onsuccess = event => resolve(event.target.result);
        request.onerror = event => reject(event.target.error);
    });
}

async function storeShoe(data) {
    const db = await openDb();
    return new Promise((resolve, reject) => {
        const transaction = db.transaction('shoes_queue', 'readwrite');
        const store = transaction.objectStore('shoes_queue');
        const request = store.put(data);
        request.onsuccess = resolve;
        request.onerror = reject;
    });
}

async function getAllQueuedShoes() {
    const db = await openDb();
    return new Promise((resolve, reject) => {
        const transaction = db.transaction('shoes_queue', 'readonly');
        const store = transaction.objectStore('shoes_queue');
        const request = store.getAll();
        request.onsuccess = () => resolve(request.result);
        request.onerror = reject;
    });
}

async function clearQueue() {
    const db = await openDb();
    return new Promise((resolve, reject) => {
        const transaction = db.transaction('shoes_queue', 'readwrite');
        const store = transaction.objectStore('shoes_queue');
        const request = store.clear();
        request.onsuccess = resolve;
        request.onerror = reject;
    });
}


// --- Service Worker Events ---

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(URLS_TO_CACHE);
            })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Cache hit - return response
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
    );
});

self.addEventListener('sync', event => {
    if (event.tag === 'sync-new-shoes') {
        event.waitUntil(syncQueuedShoes());
    }
});

async function syncQueuedShoes() {
    const queuedShoes = await getAllQueuedShoes();
    console.log('Syncing queued shoes:', queuedShoes);
    for (const shoe of queuedShoes) {
        try {
            console.log('Syncing shoe:', shoe);
            const formData = new FormData();
            formData.append('marker_number', shoe.marker_number);
            formData.append('file', shoe.file, `shoe-${shoe.marker_number}.png`);

            const response = await fetch('http://localhost:8000/add_shoe/', {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                // If a single request fails, stop and retry later
                throw new Error(`Server responded with ${response.status}`);
            }
            console.log('Successfully synced shoe:', shoe.marker_number);

        } catch (error) {
            console.error('Failed to sync shoe:', shoe.marker_number, error);
            // Stop processing so we don't lose data
            return;
        }
    }
    // If all shoes are synced successfully, clear the queue
    await clearQueue();
    console.log('Offline queue fully synced.');
}
