<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Afegir Sabata - Gestió d'Inventari</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

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

                        <?php
                        $next_marker = file_get_contents("http://192.168.0.16:8000/get_next_available_marker");
                        $next_marker = json_decode($next_marker, true);
                        ?>

                        <div class="relative">
                            <div class="flex items-center relative">
                                <input
                                    type="number"
                                    id="marker_number"
                                    name="marker_number"
                                    min="0"
                                    max="63"
                                    value="<?php echo $next_marker['next_available_marker']; ?>"
                                    required
                                    class="w-full px-4 py-4 text-lg border-2 border-gray-300 rounded-l-xl focus:border-purple-500 focus:outline-none transition-colors"
                                    inputmode="numeric">
                                <script>
                                    document.getElementById('marker_number').value = "<?php echo $next_marker['next_available_marker']; ?>";
                                </script>
                                <!-- <button type="button" id="scan-marker-btn" class="px-4 py-4 bg-purple-100 text-purple-700 rounded-r-xl border border-l-0 border-purple-300 hover:bg-purple-200 transition-colors flex items-center gap-1">
                                            <i class="fas fa-qrcode text-xl"></i>
                                    </button> -->
                                <span class="inline-flex items-center px-3 h-full rounded-r-xl border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-lg py-4">
                                    <i class="fas fa-tag text-lg"></i>
                                </span>

                            </div>
                        </div>
                        <p class="text-sm text-gray-500 ml-1">Rang: 0-63 • Següent lliure: <span id="marker-suggest"><?php echo $next_marker['next_available_marker']; ?></span></p>
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

    <script>
        // Update file name display
        function updateFileName(input) {
            const fileName = input.files[0]?.name || 'Cap fitxer seleccionat';
            document.getElementById('file-name').textContent = fileName;

            // Change the upload area style when file is selected
            const uploadArea = input.nextElementSibling.querySelector('div');
            if (input.files[0]) {
                uploadArea.classList.add('bg-green-50', 'border-green-400');
                uploadArea.classList.remove('hover:border-purple-500', 'hover:bg-purple-50');
                uploadArea.classList.add('hover:border-green-500', 'hover:bg-green-50');

                uploadArea.querySelector('i').classList.remove('text-purple-400');
                uploadArea.querySelector('i').classList.add('text-green-500');
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

            // Optional: Add your own validation here
            const marker = formData.get('marker_number');
            if (marker < 0 || marker > 63) {
                alert('El número de marcador ha d\'estar entre 0 i 63.');
                return;
            }
            if (!formData.get('file')) {
                alert('Has de seleccionar una imatge.');
                return;
            }

            try {
                const response = await fetch('http://192.168.0.16:8000/add_shoe/', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    // Show a Tailwind-styled success alert
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 z-50';
                    alertDiv.innerHTML = `
                        <i class="fas fa-check-circle text-2xl text-green-500"></i>
                        <span class="font-semibold">Sabata afegida correctament!</span>
                    `;
                    document.body.appendChild(alertDiv);
                    setTimeout(() => {
                        alertDiv.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                        setTimeout(() => alertDiv.remove(), 500);
                    }, 2000);
                    form.reset();
                    incrementMarker = parseInt(marker) + 1;
                    document.getElementById('marker_number').value = incrementMarker;
                    document.getElementById('marker-suggest').innerText = incrementMarker;

                    // Reset file input and display
                    document.getElementById('file-name').textContent = 'Cap fitxer seleccionat';

                    // Reset upload area color and icon
                    const uploadArea = document.querySelector('input#file').nextElementSibling.querySelector('div');
                    uploadArea.classList.remove('bg-green-50', 'border-green-400', 'hover:border-green-500', 'hover:bg-green-50');
                    uploadArea.classList.add('hover:border-purple-500', 'hover:bg-purple-50');
                    const uploadIcon = uploadArea.querySelector('i');
                    uploadIcon.classList.remove('text-green-500');
                    uploadIcon.classList.add('text-purple-400');
                } else {
                    const error = await response.text();
                    // Show a Tailwind-styled error alert
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 z-50';
                    errorDiv.innerHTML = `
                        <i class="fas fa-times-circle text-2xl text-red-500"></i>
                        <span class="font-semibold">Error: </span>
                        <span>${error}</span>
                    `;
                    document.body.appendChild(errorDiv);
                    setTimeout(() => {
                        errorDiv.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                        setTimeout(() => errorDiv.remove(), 500);
                    }, 3000);
                }
            } catch (err) {
                // Show a Tailwind-styled network error alert
                const netErrorDiv = document.createElement('div');
                netErrorDiv.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 z-50';
                netErrorDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                    <span class="font-semibold">Error de xarxa:</span>
                    <span>${err.message}</span>
                `;
                document.body.appendChild(netErrorDiv);
                setTimeout(() => {
                    netErrorDiv.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => netErrorDiv.remove(), 500);
                }, 3000);
            }
        });
    </script>

</html>