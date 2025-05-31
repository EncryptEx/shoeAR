<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
?>

<html !DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <meta aframe-injected="" name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,shrink-to-fit=no,user-scalable=no,minimal-ui,viewport-fit=cover">
  <meta aframe-injected="" name="mobile-web-app-capable" content="yes">
  <meta aframe-injected="" name="theme-color" content="black">
  <title>Afegir Sabata - Gestió d'Inventari</title>

  <link src="index.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="index.css">

  <script src="https://rawcdn.githack.com/stemkoski/AR.js-examples/refs/heads/master/js/aframe.min.js"></script>

  <script src="https://rawcdn.githack.com/stemkoski/AR.js-examples/refs/heads/master/js/aframe-ar.js"></script>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Mobile optimizations -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="theme-color" content="#7c3aed">
</head>

<body class="min-h-screen" cz-shortcut-listen="true">
  <?php
  // ask for available shoes
  // and create images for each one

  $response = file_get_contents("http://backend:8000/get_shoes");
  $shoes = json_decode($response, true);
  if (!is_array($shoes)) {
    $shoes = [];
  }
  ?>
  <a-scene embedded vr-mode-ui="enabled: false;" arjs="debugUIEnabled: false; detectionMode: mono_and_matrix; matrixCodeType: 3x3;">
    <a-assets>
      <?php
      // create images for each shoe
      foreach ($shoes['shoes'] as $i => $shoe) : ?>
        <img id="m<?php echo $shoe['marker']; ?>" src="http://192.168.0.27:8000/get_shoe_hd/<?php echo $shoe['marker']; ?>">
      <?php endforeach; ?>
    </a-assets>

    <?php foreach ($shoes['shoes'] as $i => $shoe) : ?>
      <a-marker type="barcode" value="<?php echo $shoe['marker']; ?>">
        <?php
        // Get image size to maintain aspect ratio
        $img_url = "http://backend:8000/get_shoe_hd/" . $shoe['marker'];
        $img_size = @getimagesize($img_url);
        if ($img_size) {
          $width = $img_size[0];
          $height = $img_size[1];
          $aspect = $width > 0 ? $height / $width : 1;
        } else {
          $aspect = 1; // fallback
        }

        $multiplier = 4;
        ?>
        <a-plane position="0 0 0"
          rotation="-90 0 0"
          width="<?php echo $multiplier; ?>"
          height="<?php echo $multiplier * round($aspect, 3); ?>"
          material="src: #m<?php echo $shoe['marker']; ?>;">
        </a-plane>
      </a-marker>
    <?php endforeach; ?>

    <a-entity camera></a-entity>

  </a-scene>
  <?php include 'navbar.php'; ?>
</body>

</html>