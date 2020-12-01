<?php

declare(strict_types=1);

namespace Tests\integration;

use Slim\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as FileUploadedFile;

class ImageTest extends BaseTestCase
{
    public function testProfilesHaveImages()
    {
        $response = $this->runApp('GET', '/profiles');
        $result = (string) $response->getBody();
        $value = json_encode(json_decode($result));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('images', $result);
        
        $resultsArray = (array)json_decode($value, true)["message"];

        // Assert user one has 3 images
        $this->assertArrayHasKey('1', $resultsArray);
        $this->assertCount(3, $resultsArray["1"]["images"]);

        // Assert user two has 2 images
          $this->assertArrayHasKey('2', $resultsArray);
          $this->assertCount(2, $resultsArray["2"]["images"]);

          // Asert user 6 has no images
        $this->assertArrayHasKey('6', $resultsArray);
          $this->assertArrayNotHasKey("images", $resultsArray["6"]);

        $this->assertStringNotContainsString('error', $result);
    }

    public function testCreateJpegs()
    {
        $files = [
            'image_01' => new UploadedFile(__DIR__.'/images/test1.jpeg','test1.jpeg', 'image/jpeg', filesize(__DIR__.'/images/test1.jpeg')),
            'image_02' => new UploadedFile(__DIR__.'/images/test2.jpeg','test2.jpeg', 'image/jpeg', filesize(__DIR__.'/images/test2.jpeg')),
        ];

        $response = $this->runApp(
            'POST', '/user/gallery',
            $files,
            true
        );

        $result = (string) $response->getBody();
        $value = json_encode(json_decode($result));

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);

        $this->assertStringContainsString('image_01', $result);
        $this->assertStringContainsString('image_02', $result);


        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('userId', $result);

        $resultsArray = (array)json_decode($value, true)["message"];
        $this->assertCount(2, $resultsArray);

        $this->assertStringNotContainsString('error', $result);
    }

    public function testCreateImageFromPngAndJpg()
    {
        $files = [
            'png_image' => new UploadedFile(__DIR__.'/images/png_image.png','png_image.png', 'image/png', filesize(__DIR__.'/images/png_image.png')),
            'jpg_image' => new UploadedFile(__DIR__.'/images/jpg_image.jpg','jpg_image.jpg', 'image/jpg', filesize(__DIR__.'/images/jpg_image.jpg')),
        ];

        $response = $this->runApp(
            'POST', '/user/gallery',
            $files,
            true
        );

        $result = (string) $response->getBody();
        $value = json_encode(json_decode($result));

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);

        $this->assertStringContainsString('jpg_image', $result);
        $this->assertStringContainsString('png_image', $result);


        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('userId', $result);

        $this->assertStringContainsString('.jpeg', $result);
        $this->assertStringNotContainsString('.png', $result);

        $resultsArray = (array)json_decode($value, true)["message"];
        $this->assertCount(2, $resultsArray);

        $this->assertStringNotContainsString('error', $result);
    }

    public function testCreateImagesWithInvalidFormats()
    {
        $files = [
            'tiff' => new UploadedFile(__DIR__.'/images/tiff_image.tiff','tiff_image.tiff', 'image/tiff', filesize(__DIR__.'/images/tiff_image.tiff')),
        ];

        $response = $this->runApp(
            'POST', '/user/gallery',
            $files,
            true
        );

        $result = (string) $response->getBody();
        $value = json_encode(json_decode($result));

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('error', $result);

        $this->assertStringContainsString("The file 'tiff_image.tiff' is not in an acceptable format - acceptable formats include: image/jpeg, image/jpg, image/png", $result);
    }
}
