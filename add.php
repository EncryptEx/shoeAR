<!doctype HTML>
<html>
<head>
    <title>Add Shoe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
        }
        label, input, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Add Shoe</h1>
    <form action="http://127.0.0.1:8000/add_shoe/" method="post" enctype="multipart/form-data">
        <label for="marker_number">Marker Number (0-63):</label>
        
        <?php
        $next_marker = file_get_contents("http://127.0.0.1:8000/get_next_available_marker");
        $next_marker = json_decode($next_marker, true);
        ?>
        <input type="number" id="marker_number" name="marker_number" min="0" max="63" value="<?php echo $next_marker['next_available_marker']; ?>" required>

        <label for="file">Shoe Image:</label>
        <input type="file" id="file" name="file" accept="image/*" capture="environment" required>

        <button type="submit">Add Shoe</button>
    </form>
</body>
</html>