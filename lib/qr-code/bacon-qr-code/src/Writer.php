<?php
// Placeholder for BaconQrCode\Writer
namespace BaconQrCode;

class Writer {
    protected $renderer;
    public function __construct(Renderer\RendererInterface $renderer) {
        $this->renderer = $renderer;
    }
    public function writeString($data) {
        // In a real library, this would generate QR code data.
        // For this placeholder, it will return a dummy string.
        return "QR_CODE_DATA_FOR:" . $data;
    }
}
