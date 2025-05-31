<?php
// Detect current file for active state
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="bg-white border-t border-gray-200 px-4 py-2 sticky bottom-0 z-50">
    <div class="flex justify-around items-center">
        <a href="add.php" class="flex flex-col items-center gap-1 py-2 px-3 <?php echo $current === 'add.php' ? 'text-purple-600' : 'text-gray-400'; ?>">
            <i class="fas fa-plus text-xl"></i>
            <span class="text-xs">Afegir</span>
        </a>
        <a href="list.php" class="flex flex-col items-center gap-1 py-2 px-3 <?php echo $current === 'list.php' ? 'text-purple-600' : 'text-gray-400'; ?>">
            <i class="fas fa-list text-xl"></i>
            <span class="text-xs">Llista</span>
        </a>
        <!-- Center Scan Button -->
        <a href="/" class="flex flex-col items-center justify-center gap-1 py-2 px-4 rounded-full bg-purple-600 text-white shadow-lg -mt-6 border-4 border-white">
            <i class="fas fa-qrcode text-2xl"></i>
            <span class="text-xs">Escanejar</span>
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