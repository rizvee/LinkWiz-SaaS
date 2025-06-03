<?php
// Placeholder for BaconQrCode\Renderer\Image\Png
namespace BaconQrCode\Renderer\Image;

use BaconQrCode\Renderer\RendererInterface;

class Png implements RendererInterface {
    protected $height = 200;
    protected $width = 200;

    public function setHeight($height) {
        $this->height = $height;
    }
    public function setWidth($width) {
        $this->width = $width;
    }
    public function render($data) {
        // In a real library, this would render a PNG image.
        // For this placeholder, it will return a dummy string representing PNG data.
        return "PNG_IMAGE_DATA_FOR:" . $data . "_SIZE:" . $this->width . "x" . $this->height;
    }
}
