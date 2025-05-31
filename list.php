<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Llista de Sabates - Gestió d'Inventari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="theme-color" content="#7c3aed">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <header class="bg-gradient-to-r from-violet-600 to-purple-600 text-white p-4 shadow-lg">
            <div class="flex items-center justify-between max-w-lg mx-auto">
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-list"></i>
                    Llista de Sabates
                </h1>
                <div class="text-sm opacity-90">
                    <i class="fas fa-box"></i> Inventari
                </div>
            </div>
        </header>
        <main class="flex-1 p-4 pb-8">
            <div class="max-w-lg mx-auto">
                <?php
                // Obtenir la llista de sabates ocupades
                $shoes = file_get_contents("http://192.168.0.16:8000/get_shoes");
                $shoes = json_decode($shoes, true)['shoes'] ?? [];
                # order by marker marker
                usort($shoes, function($a, $b) {
                    return $a['marker'] <=> $b['marker'];
                });

                

                if (!empty($shoes) && is_array($shoes)):
                ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <?php foreach ($shoes as $shoe): 

                            ?>
                            <div class="bg-white rounded-2xl shadow-xl p-4 flex flex-col items-center">
                                <div class="w-32 h-32 mb-3 flex items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-100">
                                    
                                <img src="http://192.168.0.16:8000/get_shoe/<?= urlencode($shoe['marker']) ?>" alt="Shoe #<?= htmlspecialchars($shoe['marker']) ?>" class="object-cover w-full h-full" />
                                   
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-lg font-bold text-purple-700">#<?= htmlspecialchars($shoe['marker']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php
                else:
                ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-yellow-800 text-center">
                        <i class="fas fa-info-circle mr-2"></i>No hi ha sabates ocupades a l'inventari.
                    </div>
                <?php endif; ?>
            </div>
        </main>
        <?php include 'navbar.php'; ?>
    </div>
</body>
</html>
