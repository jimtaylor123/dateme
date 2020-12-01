<?php

declare(strict_types=1);

namespace App\Service\Image;

use DateTime;
use App\Entity\Image;
use Slim\Http\Request;
use Slim\Http\UploadedFile;
use stdClass;

final class Create extends Base
{
    public function create(Request $request): array
    {
        $files = $request->getUploadedFiles();

        if(empty($files)){
            throw new \App\Exception\Image("Please provide at least one file", 400);
        }

        $images = [];
        foreach($files as $key => $file){
            $data[$key] = $this->validateImageData($file, $request);
        }

        foreach($files as $key => $file){
            $image = $this->imageRepository->createImage($data[$key], $file);
            if (self::isRedisEnabled() === true) {
                $this->saveInCache($image->getId(), $image->toJson());
            }
            $images[$key] = $image->toJson();
        }

        return $images;
    }

    private function validateImageData($file, Request $request): Image
    {
        if( ! $file instanceof UploadedFile){
            throw new \App\Exception\Image("Sorry, there is something wrong with the uploaded file - are you sure it's an image?", 400);
        }

        if(! $file->getSize()){
            throw new \App\Exception\Image("One of the files you submitted is empty, please check and try again", 400);
        }
        
        // Check mime type is acceptable image type 
        if(! in_array($file->getClientMediaType(), Image::MIME_TYPES)){
            throw new \App\Exception\Image("The file '" . $file->getClientFilename() . "' is not in an acceptable format - acceptable formats include: " . implode(", ", Image::MIME_TYPES), 400);
        };

        $authUserId = $request->getParsedBody()["decoded"]->sub;

        $newImage = new Image();
        $newImage->updateUserId($authUserId);
        $newImage->updateName(self::createUniqueName());
        $newImage->updateCreatedAt(new DateTime('today'));

        return $newImage;
    }
}
