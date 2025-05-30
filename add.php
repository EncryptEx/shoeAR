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

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <form action="http://127.0.0.1:8000/add_shoe/" method="post" enctype="multipart/form-data" class="space-y-6">
                        
                        <!-- Marker Number Input -->
                        <div class="space-y-2">
                            <label for="marker_number" class="flex items-center gap-2 text-gray-700 font-semibold text-lg">
                                <i class="fas fa-hashtag text-purple-600"></i>
                                Número de marcador
                            </label>
                            
                            <?php
                            $next_marker = file_get_contents("http://127.0.0.1:8000/get_next_available_marker");
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
                                    inputmode="numeric"
                                    >
                                    <script>
                                        document.getElementById('marker_number').value = "<?php echo $next_marker['next_available_marker']; ?>";
                                    </script>
                                    <span class="inline-flex items-center px-3 h-full rounded-r-xl border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-lg py-4">
                                        <i class="fas fa-tag text-lg"></i>
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 ml-1">Rang: 0-63 • Següent lliure: <?php echo $next_marker['next_available_marker']; ?></p>
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
                                    onchange="updateFileName(this)"
                                >
                                
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
                            class="w-full bg-gradient-to-r from-violet-600 to-purple-600 text-white font-bold text-lg py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3"
                        >
                            <i class="fas fa-plus-circle"></i>
                            Afegir sabata a l'inventari
                        </button>
                    </form>
                </div>

                <!-- Bottom spacing for mobile -->
                <div class="h-4"></div>
            </div>
        </main>

        <!-- Mobile-friendly bottom navigation (optional) -->
        <nav class="bg-white border-t border-gray-200 px-4 py-2 md:hidden sticky bottom-0 z-50">
            <div class="flex justify-around">
            <a href="#" class="flex flex-col items-center gap-1 py-2 px-3 text-purple-600">
                <i class="fas fa-plus text-xl"></i>
                <span class="text-xs">Afegir</span>
            </a>
            <a href="list.php" class="flex flex-col items-center gap-1 py-2 px-3 text-gray-400">
                <i class="fas fa-list text-xl"></i>
                <span class="text-xs">Llista</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 px-3 text-gray-400">
                <i class="fas fa-search text-xl"></i>
                <span class="text-xs">Cercar</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 px-3 text-gray-400">
                <i class="fas fa-cog text-xl"></i>
                <span class="text-xs">Configuració</span>
            </a>
            </div>
        </nav>
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
    </script>
</body>
</html>