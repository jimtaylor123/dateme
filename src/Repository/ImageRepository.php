<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Image;
use Slim\Http\UploadedFile;

final class ImageRepository extends BaseRepository
{

    public function createImage(Image $image, UploadedFile $file): Image
    {
        $path = __DIR__."/../../public/images/{$image->getName()}";
        try {

            $resizedFile = $this->resize($file);

            imagejpeg($resizedFile, $path);

            $query = '
            INSERT INTO `images`
                (`userId`, `name`, `createdAt`)
            VALUES
                (:userId, :name, :createdAt)
            ';
            $statement = $this->database->prepare($query);
    
            $userId = $image->getUserId();
            $name = $image->getName();
            $createdAt = $image->getCreatedAt();
            $statement->bindParam(':userId', $userId);
            $statement->bindParam(':name', $name);
            $statement->bindParam(':createdAt', $createdAt);
            
            $statement->execute();

        } catch (\Throwable $th) {
            if($path){
                unlink($path);
            }
            throw new \App\Exception\Image('Sorry, something went wrong, the image "' . $file->getClientFilename() . '" was not saved.', 500);
        }
       
        return $this->checkAndGetImage((int) $this->database->lastInsertId());
    }

    public function checkAndGetImage(int $imageId): Image
    {
        $query = 'SELECT * FROM `images` WHERE `id` = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $imageId);
        $statement->execute();
        $image = $statement->fetchObject(Image::class);
        if (! $image) {
            throw new \App\Exception\Image('Image not found.', 404);
        }

        return $image;
    }

    private function resize(UploadedFile $file) 
    {
        $w =100; $h = 100;
        list($width, $height) = getimagesize($file->file);

        $r = $width / $height;
        
        if ($width > $height) {
            $width = (int) ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = (int) ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
        
        $fileType = $file->getClientMediaType();

        if(strpos($fileType, 'png')){
            $src = imagecreatefrompng($file->file);
            list($width, $height) = getimagesize($file->file);
            $output = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($output,  255, 255, 255);
            imagefilledrectangle($output, 0, 0, $width, $height, $white);
            imagecopy($output, $src, 0, 0, 0, 0, $width, $height);
        } else {
            $output = imagecreatefromjpeg($file->file);
        }

        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $output, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        return $dst;
    }

    public function getUserImages(array $images) : array
    {
        if( empty($images)){
            return [];
        }

        $userIds = "(" . implode(", ", array_column($images, 'id')) . ")";

        $query = "SELECT * FROM `images` WHERE `userId` in $userIds ORDER BY userId";
        $statement = $this->database->prepare($query);
        $statement->execute();
        $images = $statement->fetchAll();

        return (array) $images;
    }
}
