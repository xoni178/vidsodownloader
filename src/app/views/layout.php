<?php
session_start();

if (isset($_GET['q'])) {
    header("Location: /");

    $_SESSION['q'] = $_GET['q'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>VidSoundDownloader</title>
</head>

<body class="bg-gray-800">
    <?php include $view ?>
</body>
<script>

</script>

</html>