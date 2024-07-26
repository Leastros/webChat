<?php

class ImageSaver {

    public static function save($source, $destination) {
        ImageSaver::compressAndSave($source, $destination, 100);
    }

    public static function compressAndSave($source, $newFilename, $maxSizeInKB): string {

        if (empty($source['name'])){
            return "";
        }

        $filename = $source['name'];

        $basename = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = $newFilename . "." . $extension;
        
        // $destinationDirectory = "./";
        // $destination = $destinationDirectory . $newFilename;
        $destination = "media" . DIRECTORY_SEPARATOR . $newFilename;
        
        $kB_size = $source['size'] / 1024;
        $quality = 100;
        if ($kB_size > $maxSizeInKB) {
            $quality = intval(($maxSizeInKB / $kB_size) * 100);
        }
        
        $info = getimagesize($source['tmp_name']);
        
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source['tmp_name']);
        } else if ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source['tmp_name']);
        } else if ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source['tmp_name']);
        } else if ($info['mime'] == 'image/bmp') {
            $image = imagecreatefrombmp($source['tmp_name']);
        } else {
            return "";
        }
        

        if (imagejpeg($image, $destination,  $quality)) {
            return "http://localhost:3000/media/" . $newFilename;
        }

        return "";
    }

    public static function checkImage($source): bool {
        // Kontrola, zda byl nahrán soubor
        if (!isset($source['tmp_name']) || empty($source['tmp_name'])) {
            return false;
        }

        // Podporované formáty obrázků
        $supportedFormats = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff'];
        
        // Získání informací o obrázku
        $info = getimagesize($source['tmp_name']);
        
        // Kontrola, zda je formát obrázku podporovaný
        if (!in_array($info['mime'], $supportedFormats)) {
            return false;
        }

        $targetWidth = 800; 
        $targetHeight = ceil($targetWidth * ($info[1] / $info[0]));

        if ($info[0] != $targetWidth || $info[1] != $targetHeight) {
            return false;
        }

        return true;
    }
}


?>
