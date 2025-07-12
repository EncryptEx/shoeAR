<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$arrContextOptions = array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Afegir Sabata - Gestió d'Inventari</title>

    <!-- Tailwind CSS -->
    <script src="tailwind.min.js"></script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Mobile optimizations -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="theme-color" content="#7c3aed">
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Mobile-first container -->
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gradient-to-r from-violet-600 to-purple-600 text-white p-4 shadow-lg">
            <div class="flex items-center justify-between max-w-lg mx-auto">
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-shoe-prints"></i>
                    Afegir Sabata
                </h1>
                <div class="text-sm opacity-90">
                    <i class="fas fa-box"></i> Inventari
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-4 pb-8">
            <div class="max-w-lg mx-auto">
                <!-- Info Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 animate-pulse">
                    <p class="text-blue-800 text-sm flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Consell: Fes una foto clara de la sabata per obtenir millors resultats
                    </p>
                </div>
                <form id="add-shoe-form" enctype="multipart/form-data" class="space-y-6">

                    <!-- Marker Number Input -->
                    <div class="space-y-2">
                        <label for="marker_number" class="flex items-center gap-2 text-gray-700 font-semibold text-lg">
                            <i class="fas fa-hashtag text-purple-600"></i>
                            Número de marcador
                        </label>

                        <div class="relative">
                            <div class="flex items-center relative">
                                <input
                                    type="number"
                                    id="marker_number"
                                    name="marker_number"
                                    min="0"
                                    max="63"
                                    value="0"
                                    required
                                    class="w-full px-4 py-4 text-lg border-2 border-gray-300 rounded-l-xl focus:border-purple-500 focus:outline-none transition-colors"
                                    inputmode="numeric">
                                <script>
                                    // Load marker data asynchronously
                                    fetch(window.location.origin.replace(/:\d+$/, ':8000') + '/get_next_available_marker/')
                                        .then(response => response.json())
                                        .then(data => {
                                            document.getElementById('marker_number').value = data.next_available_marker;
                                            document.getElementById('marker-suggest').textContent = data.next_available_marker;
                                        })
                                        .catch(err => console.error('Failed to load marker:', err));
                                </script>
                                <!-- <button type="button" id="scan-marker-btn" class="px-4 py-4 bg-purple-100 text-purple-700 rounded-r-xl border border-l-0 border-purple-300 hover:bg-purple-200 transition-colors flex items-center gap-1">
                                            <i class="fas fa-qrcode text-xl"></i>
                                    </button> -->
                                <span class="inline-flex items-center px-3 h-full rounded-r-xl border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-lg py-4">
                                    <i class="fas fa-tag text-lg"></i>
                                </span>

                            </div>
                        </div>
                        <p class="text-sm text-gray-500 ml-1">Rang: 0-63 • Següent lliure: <span id="marker-suggest">0</span></p>
                    </div>

                    <!-- Image Upload -->
                    <div class="space-y-2">
                        <label for="file" class="flex items-center gap-2 text-gray-700 font-semibold text-lg">
                            <i class="fas fa-camera text-purple-600"></i>
                            Imatge de la sabata
                        </label>

                        <!-- Custom file upload area -->
                        <div class="relative">
                            <input
                                type="file"
                                id="file"
                                name="file"
                                accept="image/*"
                                capture="environment"
                                required
                                class="hidden"
                                onchange="updateFileName(this)">

                            <label for="file" class="block w-full cursor-pointer">
                                <div class="border-2 border-dashed border-purple-300 rounded-xl p-8 text-center hover:border-purple-500 hover:bg-purple-50 transition-all">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-purple-400 mb-3"></i>
                                    <p class="text-gray-700 font-medium">Toca per fer una foto o pujar-ne una</p>
                                    <p class="text-sm text-gray-500 mt-1" id="file-name">Cap fitxer seleccionat</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-violet-600 to-purple-600 text-white font-bold text-lg py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3">
                        <i class="fas fa-plus-circle"></i>
                        Afegir sabata a l'inventari
                    </button>
                </form>
            </div>

            <!-- Bottom spacing for mobile -->
            <div class="h-4"></div>
    </div>
    </main>

    <?php include 'navbar.php'; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
    <script>
        // --- Service Worker and IndexedDB Setup ---
        // if ('serviceWorker' in navigator && 'SyncManager' in window) {
            navigator.serviceWorker.register('/sw.js')
                .then(swReg => console.log('Service Worker registered!', swReg))
                .catch(err => console.error('Service Worker registration failed:', err));
        // } else {
            // console.log('Offline sync not supported.');
        // }

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
                console.log('Storing shoe data:', data);
                const request = store.put(data);
                request.onsuccess = resolve;
                request.onerror = reject;
            });
        }


        // Update file name display
        function updateFileName(input) {
            const file = input.files[0];
            document.getElementById('file-name').textContent = file ? file.name : 'Cap fitxer seleccionat';

            // Change the upload area style when file is selected
            const uploadArea = input.nextElementSibling.querySelector('div');
            if (file) {
                uploadArea.classList.add('bg-green-50', 'border-green-400');
                uploadArea.classList.remove('hover:border-purple-500', 'hover:bg-purple-50');
                uploadArea.classList.add('hover:border-green-500', 'hover:bg-green-50');

                uploadArea.querySelector('i').classList.remove('text-purple-400');
                uploadArea.querySelector('i').classList.add('text-green-500');
            }

            // --- EXIF orientation fix ---
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        EXIF.getData(img, function() {
                            const orientation = EXIF.getTag(this, "Orientation");
                            // alert(`EXIF Orientation: ${orientation}`);
                            let drawWidth = img.width;
                            let drawHeight = img.height;
                            let canvas = document.createElement('canvas');
                            let ctx = canvas.getContext('2d');

                            if (orientation && orientation !== 1) {
                                // Set canvas size and transform for each orientation (EXIF spec)
                                switch (orientation) {
                                    case 2: // horizontal flip
                                        canvas.width = drawWidth;
                                        canvas.height = drawHeight;
                                        ctx.translate(drawWidth, 0);
                                        ctx.scale(-1, 1);
                                        break;
                                    case 3: // 180°
                                        canvas.width = drawWidth;
                                        canvas.height = drawHeight; // FIXED
                                        // ctx.translate(drawWidth, drawHeight);
                                        // ctx.rotate(Math.PI);
                                        break;
                                    case 4: // vertical flip
                                        canvas.width = drawWidth;
                                        canvas.height = drawHeight;
                                        ctx.translate(0, drawHeight);
                                        ctx.scale(1, -1);
                                        break;
                                    case 5: // vertical flip + 90° CW
                                        canvas.width = drawHeight;
                                        canvas.height = drawWidth;
                                        ctx.rotate(0.5 * Math.PI);
                                        ctx.scale(1, -1);
                                        break;
                                    case 6: // 90° CW
                                        canvas.width = drawWidth;
                                        canvas.height = drawHeight; // FIXED
                                        
                                        // ctx.rotate(-0.5 * Math.PI);
                                        // ctx.translate(-drawWidth, 0);
                                        break;
                                    case 7: // horizontal flip + 90° CW
                                        canvas.width = drawHeight;
                                        canvas.height = drawWidth;
                                        ctx.rotate(0.5 * Math.PI);
                                        ctx.translate(drawWidth, -drawHeight);
                                        ctx.scale(-1, 1);
                                        break;
                                    case 8: // 270° CW
                                        canvas.width = drawHeight;
                                        canvas.height = drawWidth;
                                        ctx.rotate(-0.5 * Math.PI);
                                        ctx.translate(-drawWidth, 0); //TODO
                                        break;
                                    default:
                                        canvas.width = drawWidth;
                                        canvas.height = drawHeight;
                                }
                                

                                    ctx.drawImage(img, 0, 0);
                                    canvas.toBlob(function(blob) {
                                        const newFile = new File([blob], file.name, {
                                            type: file.type
                                        });
                                        const dataTransfer = new DataTransfer();
                                        dataTransfer.items.add(newFile);
                                        input.files = dataTransfer.files;
                                    }, file.type);
                                
                            }
                        });
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Prevent zoom on input focus (iOS)
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="number"], input[type="text"]');
            inputs.forEach(input => {
                input.style.fontSize = '16px';
            });
        });


        document.getElementById('add-shoe-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const marker = formData.get('marker_number');
            const file = formData.get('file');

            if (marker < 0 || marker > 63) {
                alert('El número de marcador ha d\'estar entre 0 i 63.');
                return;
            }
            if (!file || file.size === 0) {
                alert('Has de seleccionar una imatge.');
                return;
            }

            // If offline, queue the submission
            if (!navigator.onLine) {
                console.log('Offline: Queuing submission.');
                queueSubmission(marker, file);
                return;
            }

            try {
                // PROD: // const response = await fetch('https://192.168.0.27:8000/add_shoe/', {
                // TEST:
                const response = await fetch('http://localhost:8000/add_shoe/', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    showSuccessAlert('Sabata afegida correctament!');
                    resetForm(form, marker);
                } else {
                    const error = await response.text();
                    showErrorAlert(`Error: ${error}`);
                }
            } catch (err) {
                console.log('Fetch failed, queuing submission.', err);
                queueSubmission(marker, file);
            }
        });

        function queueSubmission(marker, file) {
            console.log('Storing shoe data:', { marker_number: marker, file: file });
            storeShoe({ marker_number: marker, file: file })
                .then(() => {
                    return navigator.serviceWorker.ready;
                })
                .then(swReg => {
                    return swReg.sync.register('sync-new-shoes');
                })
                .then(() => {
                    showSuccessAlert('Acció desada. S\'enviarà quan hi hagi connexió.');
                    console.log('marker: ', marker, 'file:', file.name);
                    resetForm(document.getElementById('add-shoe-form'), marker);
                })
                .catch(err => {
                    showErrorAlert('No s\'ha pogut desar l\'acció per a enviar més tard.');
                    console.error(err);
                });
        }

        function showSuccessAlert(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 z-50';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle text-2xl text-green-500"></i>
                <span class="font-semibold">${message}</span>
            `;
            document.body.appendChild(alertDiv);
            setTimeout(() => {
                alertDiv.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => alertDiv.remove(), 500);
            }, 2500);
        }

        function showErrorAlert(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 z-50';
            errorDiv.innerHTML = `
                <i class="fas fa-times-circle text-2xl text-red-500"></i>
                <span class="font-semibold">${message}</span>
            `;
            document.body.appendChild(errorDiv);
            setTimeout(() => {
                errorDiv.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => errorDiv.remove(), 500);
            }, 3000);
        }

        function resetForm(form, lastMarker) {
            form.reset();
            const incrementMarker = parseInt(lastMarker) + 1;
            document.getElementById('marker_number').value = incrementMarker;
            document.getElementById('marker-suggest').innerText = incrementMarker;
            document.getElementById('file-name').textContent = 'Cap fitxer seleccionat';

            const uploadArea = document.querySelector('input#file').nextElementSibling.querySelector('div');
            uploadArea.classList.remove('bg-green-50', 'border-green-400', 'hover:border-green-500', 'hover:bg-green-50');
            uploadArea.classList.add('hover:border-purple-500', 'hover:bg-purple-50');
            const uploadIcon = uploadArea.querySelector('i');
            uploadIcon.classList.remove('text-green-500');
            uploadIcon.classList.add('text-purple-400');
        }
    </script>

</html>