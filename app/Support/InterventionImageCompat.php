<?php

declare(strict_types=1);

namespace Intervention\Image;

class ImageManager
{
    public function __construct(mixed $driver = null)
    {
        // Intentionally ignored. The vendor captcha package only needs a
        // manager instance that can create a drawable image.
    }

    public function create(int $width, int $height): CompatImage
    {
        return new CompatImage($width, $height);
    }
}

class CompatImage
{
    private \GdImage $canvas;

    public function __construct(private int $width, private int $height)
    {
        $canvas = imagecreatetruecolor($width, $height);

        if (! $canvas instanceof \GdImage) {
            throw new \RuntimeException('Failed to create captcha image canvas.');
        }

        imagesavealpha($canvas, true);
        $background = imagecolorallocatealpha($canvas, 255, 255, 255, 127);

        imagealphablending($canvas, false);
        imagefill($canvas, 0, 0, $background);
        imagecolortransparent($canvas, $background);
        imageresolution($canvas, 72, 72);

        $this->canvas = $canvas;
    }

    public function text(string $text, int $x, int $y, callable $callback): self
    {
        $font = new class
        {
            public string $file = '';
            public int $size = 24;
            public string $color = '#333';

            public function file(string $path): void
            {
                $this->file = $path;
            }

            public function size(int $size): void
            {
                $this->size = $size;
            }

            public function color(string $color): void
            {
                $this->color = $color;
            }

            public function align(string $align): void
            {
                // No-op, kept for vendor compatibility.
            }

            public function valign(string $valign): void
            {
                // No-op, kept for vendor compatibility.
            }
        };

        $callback($font);

        $rgb = $this->hexToRgb($font->color);
        $color = imagecolorallocate($this->canvas, $rgb[0], $rgb[1], $rgb[2]);

        if (is_file($font->file)) {
            $bbox = imagettfbbox($font->size, 0, $font->file, $text);
            $textWidth = abs($bbox[2] - $bbox[0]);
            $textHeight = abs($bbox[7] - $bbox[1]);

            $drawX = (int) round($x - ($textWidth / 2));
            $drawY = (int) round($y + ($textHeight / 2));

            imagettftext($this->canvas, $font->size, 0, $drawX, $drawY, $color, $font->file, $text);
        } else {
            imagestring($this->canvas, 5, $x, $y, $text, $color);
        }

        return $this;
    }

    public function toPng(): string
    {
        ob_start();
        imagepng($this->canvas);

        return (string) ob_get_clean();
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = preg_replace('/(.)/', '$1$1', $hex);
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
