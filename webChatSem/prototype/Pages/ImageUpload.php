<?php

require 'Services/ImageSaver.php';


if (isset($_POST['upload_avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {

    $filename = $_FILES['avatar']['name'];
    $basename = pathinfo($filename, PATHINFO_FILENAME);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $newFilename = "uploaded" . "." . $extension;
    
    $source = $_FILES['avatar'];
    $destinationDirectory = "../media/";
    $destinationPath = $destinationDirectory . $newFilename;

    $maxSizeKB = $_POST['quality'];
    $sizeKB = $source['size'] / 1024;

    $newQuality = 1;
    if ($sizeKB > $maxSizeKB) {
        $newQuality = $maxSizeKB / $sizeKB;
    }
    $quality = intval($newQuality * 100);
    ImageSaver::compressAndSave($source, $destinationPath, $quality);

}

?>


<div>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="avatar">Vybrat obrázek: </label>
        <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg" />
        <label for="quality">Max velikost (kB): </label>
        <input type="number" min="128" max="8192" value="1024" name="quality" id="quality">
        <input type="submit" value="Odeslat" name="upload_avatar" id="upload_avatar">
    </form>
    <div>
        <?php
        if (isset($_POST["upload_avatar"])) {
            echo "Puvodni velikost: <b>$sizeKB kB</b><br>";
            echo "Max velikost: <b>$maxSizeKB kB</b><br>";
            echo "Kvalita: <b>" . $quality . " %</b><br>";
            echo "Nova velikost (+/-): <b>" . ($quality / 100) * $sizeKB . " kB</b><br>";
        }
        ?>
    </div>
    <div>
        <?php if (file_exists("Media/uploaded.jpg")) { ?>
            <img width="600" src="Media/uploaded.jpg" alt="Uploaded Image">
        <?php } ?>
    </div>
</div>

<style lang="scss" scoped>
    form {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;

        input{
            margin-bottom: 16px;
        }
    
        label{
            margin-bottom: 4px;
        }
    }

</style>