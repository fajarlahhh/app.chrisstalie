<?php

namespace App\Helpers;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

class QrCode
{
    protected $size = 300;

    protected $margin = 10;

    protected $format = 'svg';

    public static function size($size)
    {
        $instance = new self;
        $instance->size = $size;

        return $instance;
    }

    public static function format($format)
    {
        $instance = new self;
        $instance->format = $format;

        return $instance;
    }

    public function generate($text)
    {
        $qrCode = new EndroidQrCode(
            data: $text,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $this->size,
            margin: $this->margin,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        if ($this->format === 'png') {
            $writer = new PngWriter;
            $result = $writer->write($qrCode);

            return '<img src="data:image/png;base64,'.base64_encode($result->getString()).'" alt="QR Code">';
        }

        $writer = new SvgWriter;
        $result = $writer->write($qrCode);

        return $result->getString();
    }
}
