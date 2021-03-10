<?php

if(isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])) {
    $photo = $_FILES['photo'];

    $maxWidth = 400;
    $maxHeight = 400;

    if(in_array($photo['type'], ['image/png', 'image/jpg', 'image/jpeg'])) {

        list($widthOrig, $heightOrig) = getimagesize($photo['tmp_name']);
        $ratio = $widthOrig / $heightOrig;

        $newWidth = $maxWidth;
        $newHeight = $maxHeight;
        $ratioMax = $maxWidth / $maxHeight;

        if($ratioMax > $ratio) {
            $newWidth = $newHeight * $ratio;
        } else {
            $newHeight = $newWidth / $ratio;
        }

        $finalImage = imagecreatetruecolor($newWidth, $newHeight);
        switch($photo['type']) {
            case 'image/png':
                $image = imagecreatefrompng($photo['tmp_name']);
            break;
            case 'image/jpg':
            case 'image/jpeg':
                $image = imagecreatefromjpeg($photo['tmp_name']);
            break;
        }

        imagecopyresampled(
            $finalImage, $image,
            0, 0, 0, 0,
            $newWidth, $newHeight, $widthOrig, $heightOrig
        );

        $photoName = md5(time().rand(0,9999)).'.jpg';
        imagejpeg($finalImage, 'assets/media/'.$photoName);

        echo 'Arquivo redimensionado e salvo com sucesso!';

    } else {
        echo 'Arquivo não permitido!';
    }

} else {
    echo 'Nenhuma imagem enviada';
}