<?php

class ImageSaverOld {

    public static function save($source, $destination) {
        ImageSaverOld::compressAndSave($source, $destination, 100);
    }

    public static function compressAndSave($source, $destination, $maxSizeInKB) {

        $kB_size = $source['size'] / 1000;
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
        }

        imagejpeg($image, $destination, $quality);
    }
}


?>