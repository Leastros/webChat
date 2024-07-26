<!DOCTYPE html>
<?php
ini_set('session.gc_maxlifetime', 1200);
session_set_cookie_params(1800);
session_start();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <script src="//unpkg.com/alpinejs" defer></script>

    <?php

    $indexDir = dirname($_SERVER['SCRIPT_NAME']);

    // windows to unix path
    $indexDir = str_replace('\\', '/', $indexDir);

    $stylePath = "$indexDir/style.css";

    // strip double slashes from path ('//style.css')
    $stylePath = preg_replace('#/+#', '/', $stylePath);

    $stylePath = htmlentities($stylePath);

    echo "<link rel='stylesheet' href='$stylePath' >";

    ?>

</head>

<body data-bs-theme="dark" class="d-flex flex-column" style="min-height: 100vh;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <?php include './pages/includes/navbar.php'; ?>

    <?php

    $environment = getenv("ENVIRONMENT");

    // development
    if ($environment != 'prod') {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    if (isset($_GET['page'])) {
        $page = rtrim($_GET['page'], '/');
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        $page_file = "pages/{$page}.php";
        // $page_file = "pages/{$page}";

        if (file_exists($page_file)) {
            require $page_file;
        } else {
            // page not found
            header("HTTP/1.0 404 Not Found");
            include('pages/404.php');
        }
    } else {
        // redirect
        header('Location: ?page=login');
    }

    ?>

</body>

</html>
