<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test for image upload</title>
</head>
<body>

    <div class="nav">
    <?php
    $files = scandir('Pages/');

    foreach ($files as $file) {
        $filename = pathinfo($file, PATHINFO_FILENAME);
        echo "<a href='index.php?page=$filename'>$filename</a>"  ;
    }
    ?>
    </div>

    <div class="content">
        <?php
        $page_file = "";
        if (isset($_GET['page'])) {
            $page_name = $_GET['page'];
            $page_file = "pages/{$page_name}.php";
        }

        if (file_exists($page_file)) {
            require $page_file;
        } 
        ?>
    </div>
</body>
</html>

<style lang="scss">
    .nav {
        display: flex;
        flex-direction: row;
        padding: 32px 0 32px 0;
        border-bottom: 1px solid black;
        * {
            margin-right: 16px;
        }
    }

    .content {
        padding: 32px 0 32px 0;
    }
</style>