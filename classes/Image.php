<?php
class Image
{
  private $api_key = "YOUR_API_KEY_HERE";
  private $api_url = "https://api.imgbb.com/1/upload";
  private $img;
  private $compressedImage;
  private $sourceImage;
  private $extension;
  private $name;
  private $name_with_extension;
  private $img_info;
  private $destination;

  public function __construct($img)
  {
    if (is_array($img) && isset($img["tmp_name"]) && is_uploaded_file($img["tmp_name"])) {
      $this->img = $img["tmp_name"];
      $this->img_info = getimagesize($this->img);
      $this->extension = image_type_to_extension($this->img_info[2], true);
      $this->name = pathinfo($img['name'], PATHINFO_FILENAME);
    } else {
      $this->img = $img;

      $urlParts = parse_url($img);
      if (isset($urlParts['path'])) {
        $pathParts = pathinfo($urlParts['path']);
        if (isset($pathParts['extension'])) {
          $this->extension = $pathParts['extension'];
        }
      }

      $this->name = pathinfo($img, PATHINFO_FILENAME);
      $this->img_info = getimagesize($this->img);
    }
    $this->name_with_extension = "$this->name.$this->extension";
    $imageType = exif_imagetype($this->img);
    if ($imageType === IMAGETYPE_PNG) {
      $this->sourceImage = imagecreatefrompng($this->img);
    } elseif ($imageType === IMAGETYPE_JPEG) {
      $this->sourceImage = imagecreatefromjpeg($this->img);
    } elseif ($imageType === IMAGETYPE_GIF) {
      $this->sourceImage = imagecreatefromgif($this->img);
    } elseif ($imageType === IMAGETYPE_WEBP) {
      $this->sourceImage = imagecreatefromwebp($this->img);
    }
    $this->compressedImage = imagecreatetruecolor($this->img_info[0], $this->img_info[1]);
    imagecopyresampled(
      $this->compressedImage,
      $this->sourceImage,
      0,
      0,
      0,
      0,
      $this->img_info[0], $this->img_info[1],
      $this->img_info[0], $this->img_info[1]
    );
    $this->destination = $_SERVER["DOCUMENT_ROOT"] . "/temp/";
  }


  public function compress()
  {
    imagejpeg($this->compressedImage, $this->destination . "medium_80_" . $this->name_with_extension, 80);
    imagejpeg($this->compressedImage, $this->destination . "tiny_20_" . $this->name_with_extension, 20);
    imagedestroy($this->sourceImage);
    imagedestroy($this->compressedImage);
  }

  public function upload($img_file = null)
  {
    if (empty($img_file)) {
      $img_file = $this->img;
    }

    $img_file = file_get_contents($img_file);
    $ch = curl_init($this->api_url);
    $postFields = [
      'key' => $this->api_key,
      'image' => base64_encode($img_file)
    ];
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $responseData = json_decode($response, true);
    return $responseData['data']['url'] ?? null;
  }


  public function compressAndUpload()
  {
    $this->compress();
    $original_image_url = $this->upload($this->img);
    $medium_image_url = $this->upload($this->destination . "medium_80_" . $this->name_with_extension);
    $tiny_image_url = $this->upload($this->destination . "tiny_20_" . $this->name_with_extension);
    return [
      'original' => $original_image_url,
      'medium' => $medium_image_url,
      'tiny' => $tiny_image_url
    ];
  }
}