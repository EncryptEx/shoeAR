<html data-lt-installed="true"><head><link rel="icon" href="data:;base64,iVBORw0KGgo=">
<meta aframe-injected="" name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,shrink-to-fit=no,user-scalable=no,minimal-ui,viewport-fit=cover"><meta aframe-injected="" name="mobile-web-app-capable" content="yes"><meta aframe-injected="" name="theme-color" content="black">

<script src="https://rawcdn.githack.com/stemkoski/AR.js-examples/refs/heads/master/js/aframe.min.js"></script>

<link src="index.css" rel="stylesheet" type="text/css">
<script src="https://rawcdn.githack.com/stemkoski/AR.js-examples/refs/heads/master/js/aframe-ar.js"></script>
<body style="margin: 0px 0px 0px -99.5px; overflow: hidden; width: 1004px; height: 753px;" cz-shortcut-listen="true">
 <?php

  // ask for available shoes
  // and create images for each one

  $response = file_get_contents("http://127.0.1:8000/get_shoes");
  $shoes = json_decode($response, true);
  if (!is_array($shoes)) {
    $shoes = [];
  }

  // die(var_dump($shoes['shoes']));
  ?>
  <a-scene embedded vr-mode-ui="enabled: false;" arjs="debugUIEnabled: false; detectionMode: mono_and_matrix; matrixCodeType: 3x3;">

    <a-assets>
      <?php
      // create images for each shoe
      foreach ($shoes['shoes'] as $i => $shoe) : ?>
        <img id="m<?php echo $shoe['marker']; ?>" src="http://127.0.0.1:8000/get_shoe/<?php echo $shoe['marker']; ?>">
      <?php endforeach; ?>



    </a-assets>

    <?php foreach ($shoes['shoes'] as $i => $shoe) : ?>
      <a-marker type="barcode" value="<?php echo $shoe['marker']; ?>">
        <?php
          // Get image size to maintain aspect ratio
          $img_url = "http://127.0.0.1:8000/get_shoe/" . $shoe['marker'];
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
          height="<?php echo $multiplier*round($aspect, 3); ?>"
          material="src: #m<?php echo $shoe['marker']; ?>;">
        </a-plane>
      </a-marker>
    <?php endforeach; ?>

    <a-entity camera></a-entity>

  </a-scene>
</body>

</html>