<?php

namespace App\Services\Thumbnail;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ThumbnailGenerator
{
    protected int $width = 1200;
    protected int $height = 630;

    protected array $fonts = [
        'arial' => 'Arial',
        'impact' => 'Impact',
        'georgia' => 'Georgia',
        'verdana' => 'Verdana',
    ];

    protected array $colorSchemes = [
        'dark' => [
            'overlay' => [0, 0, 0, 80],
            'text' => [255, 255, 255],
            'accent' => [255, 193, 7],
        ],
        'light' => [
            'overlay' => [255, 255, 255, 80],
            'text' => [33, 33, 33],
            'accent' => [0, 123, 255],
        ],
        'blue' => [
            'overlay' => [0, 48, 87, 85],
            'text' => [255, 255, 255],
            'accent' => [0, 191, 255],
        ],
        'purple' => [
            'overlay' => [75, 0, 130, 80],
            'text' => [255, 255, 255],
            'accent' => [255, 105, 180],
        ],
        'green' => [
            'overlay' => [0, 60, 40, 80],
            'text' => [255, 255, 255],
            'accent' => [0, 255, 127],
        ],
        'red' => [
            'overlay' => [100, 20, 20, 85],
            'text' => [255, 255, 255],
            'accent' => [255, 99, 71],
        ],
        'gradient_blue' => [
            'overlay' => [30, 60, 114, 75],
            'text' => [255, 255, 255],
            'accent' => [66, 165, 245],
        ],
        'gradient_orange' => [
            'overlay' => [180, 60, 30, 80],
            'text' => [255, 255, 255],
            'accent' => [255, 167, 38],
        ],
    ];

    protected array $textPositions = ['center', 'top', 'bottom'];

    /**
     * Generate a thumbnail image with title text.
     *
     * @param string $title The title text to display
     * @param array $options Optional customization options
     * @return string|null Path to the generated image or null on failure
     */
    public function generate(string $title, array $options = []): ?string
    {
        // Get background images from settings
        $backgroundImages = $this->getBackgroundImages();

        if (empty($backgroundImages)) {
            return $this->generateSolidColorThumbnail($title, $options);
        }

        // Pick random background
        $backgroundPath = $backgroundImages[array_rand($backgroundImages)];

        return $this->generateWithBackground($title, $backgroundPath, $options);
    }

    /**
     * Generate thumbnail with a background image.
     */
    protected function generateWithBackground(string $title, string $backgroundPath, array $options = []): ?string
    {
        $disk = Storage::disk(config('filesystems.default'));
        
        // Check if background exists in storage
        if (!$disk->exists($backgroundPath)) {
            return $this->generateSolidColorThumbnail($title, $options);
        }
        
        try {
            // Download the background image to a temp file
            $imageContent = $disk->get($backgroundPath);
            $tempPath = sys_get_temp_dir() . '/' . Str::uuid() . '.tmp';
            file_put_contents($tempPath, $imageContent);
            
            // Determine image type
            $imageInfo = @getimagesize($tempPath);
            if (!$imageInfo) {
                @unlink($tempPath);
                return $this->generateSolidColorThumbnail($title, $options);
            }
            
            // Load source image based on type
            $sourceImage = match ($imageInfo[2]) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($tempPath),
                IMAGETYPE_PNG => imagecreatefrompng($tempPath),
                IMAGETYPE_WEBP => imagecreatefromwebp($tempPath),
                IMAGETYPE_GIF => imagecreatefromgif($tempPath),
                default => null,
            };
            
            // Clean up temp file
            @unlink($tempPath);
            
            if (!$sourceImage) {
                return $this->generateSolidColorThumbnail($title, $options);
            }
        } catch (\Exception $e) {
            Log::error('Failed to load background image', ['error' => $e->getMessage()]);
            return $this->generateSolidColorThumbnail($title, $options);
        }

        // Create destination image
        $image = imagecreatetruecolor($this->width, $this->height);

        // Resize and crop source to fit
        $srcWidth = imagesx($sourceImage);
        $srcHeight = imagesy($sourceImage);

        // Calculate crop dimensions
        $srcRatio = $srcWidth / $srcHeight;
        $dstRatio = $this->width / $this->height;

        if ($srcRatio > $dstRatio) {
            $cropWidth = (int) ($srcHeight * $dstRatio);
            $cropHeight = $srcHeight;
            $cropX = (int) (($srcWidth - $cropWidth) / 2);
            $cropY = 0;
        } else {
            $cropWidth = $srcWidth;
            $cropHeight = (int) ($srcWidth / $dstRatio);
            $cropX = 0;
            $cropY = (int) (($srcHeight - $cropHeight) / 2);
        }

        imagecopyresampled(
            $image,
            $sourceImage,
            0,
            0,
            $cropX,
            $cropY,
            $this->width,
            $this->height,
            $cropWidth,
            $cropHeight
        );

        imagedestroy($sourceImage);

        // Apply overlay and text
        $this->applyOverlayAndText($image, $title, $options);

        // Save image
        return $this->saveImage($image);
    }

    /**
     * Generate thumbnail with solid color background.
     */
    protected function generateSolidColorThumbnail(string $title, array $options = []): ?string
    {
        $image = imagecreatetruecolor($this->width, $this->height);

        // Random gradient colors
        $gradients = [
            [[44, 62, 80], [52, 152, 219]],
            [[142, 68, 173], [155, 89, 182]],
            [[41, 128, 185], [44, 62, 80]],
            [[231, 76, 60], [192, 57, 43]],
            [[39, 174, 96], [46, 204, 113]],
            [[241, 196, 15], [243, 156, 18]],
            [[52, 73, 94], [149, 165, 166]],
            [[26, 188, 156], [22, 160, 133]],
        ];

        $gradient = $gradients[array_rand($gradients)];
        $this->fillGradient($image, $gradient[0], $gradient[1]);

        // Apply text (no additional overlay needed for solid colors)
        $this->applyOverlayAndText($image, $title, array_merge($options, ['skip_overlay' => true]));

        return $this->saveImage($image);
    }

    /**
     * Fill image with gradient.
     */
    protected function fillGradient($image, array $colorStart, array $colorEnd): void
    {
        for ($y = 0; $y < $this->height; $y++) {
            $ratio = $y / $this->height;

            $r = (int) ($colorStart[0] + ($colorEnd[0] - $colorStart[0]) * $ratio);
            $g = (int) ($colorStart[1] + ($colorEnd[1] - $colorStart[1]) * $ratio);
            $b = (int) ($colorStart[2] + ($colorEnd[2] - $colorStart[2]) * $ratio);

            $color = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, $this->width, $y, $color);
        }
    }

    /**
     * Apply overlay and text to image.
     */
    protected function applyOverlayAndText($image, string $title, array $options = []): void
    {
        // Pick random color scheme
        $schemeKey = $options['color_scheme'] ?? array_rand($this->colorSchemes);
        $scheme = $this->colorSchemes[$schemeKey];

        // Apply semi-transparent overlay (unless skipped)
        if (empty($options['skip_overlay'])) {
            $overlayColor = imagecolorallocatealpha(
                $image,
                $scheme['overlay'][0],
                $scheme['overlay'][1],
                $scheme['overlay'][2],
                (int) (127 * ($scheme['overlay'][3] / 100))
            );
            imagefilledrectangle($image, 0, 0, $this->width, $this->height, $overlayColor);
        }

        // Pick random text position
        $position = $options['position'] ?? $this->textPositions[array_rand($this->textPositions)];

        // Get font path (use default GD font if custom fonts not available)
        $fontPath = $this->getFontPath();
        $fontSize = $this->calculateFontSize($title);

        // Text color
        $textColor = imagecolorallocate($image, $scheme['text'][0], $scheme['text'][1], $scheme['text'][2]);

        // Wrap text
        $wrappedText = $this->wrapText($title, $fontSize, $fontPath);

        // Calculate text position
        $textBox = imagettfbbox($fontSize, 0, $fontPath, $wrappedText);
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);

        $x = (int) (($this->width - $textWidth) / 2);
        $y = match ($position) {
            'top' => (int) ($this->height * 0.25),
            'bottom' => (int) ($this->height * 0.75),
            default => (int) (($this->height + $textHeight) / 2),
        };

        // Add text shadow for better readability
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 60);
        imagettftext($image, $fontSize, 0, $x + 3, $y + 3, $shadowColor, $fontPath, $wrappedText);

        // Draw main text
        imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $wrappedText);

        // Add accent line
        $accentColor = imagecolorallocate($image, $scheme['accent'][0], $scheme['accent'][1], $scheme['accent'][2]);
        $lineY = match ($position) {
            'top' => $y + 30,
            'bottom' => $y - $textHeight - 20,
            default => $y + 30,
        };
        $lineWidth = min(300, $textWidth);
        $lineX = (int) (($this->width - $lineWidth) / 2);
        imagefilledrectangle($image, $lineX, $lineY, $lineX + $lineWidth, $lineY + 4, $accentColor);
    }

    /**
     * Calculate appropriate font size based on text length.
     */
    protected function calculateFontSize(string $text): int
    {
        $length = mb_strlen($text);

        return match (true) {
            $length <= 30 => 56,
            $length <= 50 => 48,
            $length <= 80 => 40,
            $length <= 120 => 32,
            default => 28,
        };
    }

    /**
     * Wrap text to fit within image width.
     */
    protected function wrapText(string $text, int $fontSize, string $fontPath): string
    {
        $maxWidth = $this->width - 100;
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine ? "$currentLine $word" : $word;
            $testBox = imagettfbbox($fontSize, 0, $fontPath, $testLine);
            $testWidth = abs($testBox[4] - $testBox[0]);

            if ($testWidth > $maxWidth && $currentLine) {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine = $testLine;
            }
        }

        if ($currentLine) {
            $lines[] = $currentLine;
        }

        // Limit to 3 lines
        if (count($lines) > 3) {
            $lines = array_slice($lines, 0, 3);
            $lines[2] = rtrim($lines[2], '.') . '...';
        }

        return implode("\n", $lines);
    }

    /**
     * Get font path.
     */
    protected function getFontPath(): string
    {
        // Check for custom fonts in storage
        $customFonts = glob(storage_path('app/fonts/*.ttf'));

        if (!empty($customFonts)) {
            return $customFonts[array_rand($customFonts)];
        }

        // Fallback to system fonts
        $systemFonts = [
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/impact.ttf',
            'C:/Windows/Fonts/georgia.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
        ];

        foreach ($systemFonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }

        // Ultimate fallback - this should exist on most systems
        return 'arial';
    }

    /**
     * Save image and return path.
     */
    protected function saveImage($image): ?string
    {
        $filename = 'thumbnails/' . Str::uuid() . '.jpg';
        
        // Create a temporary file to save the image
        $tempPath = sys_get_temp_dir() . '/' . Str::uuid() . '.jpg';
        
        // Save as JPEG with high quality to temp file
        $result = imagejpeg($image, $tempPath, 90);
        imagedestroy($image);
        
        if (!$result || !file_exists($tempPath)) {
            return null;
        }
        
        try {
            // Read the temp file and upload to storage
            $imageContent = file_get_contents($tempPath);
            $disk = Storage::disk(config('filesystems.default'));
            
            // Upload to storage (works for both local and S3)
            $uploaded = $disk->put($filename, $imageContent, 'public');
            
            // Clean up temp file
            @unlink($tempPath);
            
            if ($uploaded) {
                return $filename;
            }
            
            return null;
        } catch (\Exception $e) {
            // Clean up temp file on error
            @unlink($tempPath);
            Log::error('Thumbnail upload failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get background images from settings.
     */
    protected function getBackgroundImages(): array
    {
        $images = Setting::get('thumbnail_images', []);

        if (is_string($images)) {
            $images = json_decode($images, true) ?? [];
        }

        return array_filter($images, function ($path) {
            return Storage::disk(config('filesystems.default'))->exists($path);
        });
    }

    /**
     * Set custom dimensions.
     */
    public function setDimensions(int $width, int $height): self
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }
}
