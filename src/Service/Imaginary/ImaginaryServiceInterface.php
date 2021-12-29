<?php

namespace App\Service\Imaginary;

/**
 * Interface ImaginaryServiceInterface
 * @package App\Service
 */
interface ImaginaryServiceInterface
{
    /**
     * @return static
     */
    public static function new(): self;

    /**
     * @param string $uploadFilePath
     * @return $this
     */
    public function setUploadFilePath(string $uploadFilePath): self;

    /**
     * @param string $operation
     * @param array $params
     * @return $this
     */
    public function addOperation(string $operation, array $params = []): self;

    /**
     * https://github.com/h2non/imaginary#get--post-crop
     * @param int $width
     * @param int $height
     * @param array $params
     * @return $this
     */
    public function crop(int $width, int $height, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-smartcrop
     * @param int $width
     * @param int $height
     * @param array $params
     * @return $this
     */
    public function smartCrop(int $width, int $height, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-resize
     * @param int $width
     * @param int $height
     * @param array $params
     * @return $this
     */
    public function resize(int $width, int $height, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-enlarge
     * @param int $width
     * @param int $height
     * @param array $params
     * @return $this
     */
    public function enlarge(int $width, int $height, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-extract
     * @param int $top
     * @param int $left
     * @param int $areaWidth
     * @param int $areaHeight
     * @param array $params
     * @return $this
     */
    public function extract(int $top, int $left, int $areaWidth, int $areaHeight, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-zoom
     * @param int $factor
     * @param array $params
     * @return $this
     */
    public function zoom(int $factor, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-thumbnail
     * @param int $width
     * @param int $height
     * @param array $params
     * @return $this
     */
    public function thumbnail(int $width, int $height, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-fit
     * @param int $width
     * @param int $height
     * @param array $params
     * @return $this
     */
    public function fit(int $width, int $height, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-rotate
     * @param int $deg
     * @param array $params
     * @return $this
     */
    public function rotate(int $deg, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-flip
     * @param int $width
     * @param int $height
     * @param array $params
     * @return $this
     */
    public function flip(int $width, int $height, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-flop
     * @param int $width
     * @param int $height
     * @param array $params
     * @return $this
     */
    public function flop(int $width, int $height, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-convert
     * @param string $type
     * @param array $params
     * @return $this
     */
    public function convert(string $type, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-watermark
     * @param string $text
     * @param array $params
     * @return $this
     */
    public function watermark(string $text, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-watermarkimage
     * @param string $image Url watermark, NOT PATH
     * @param int $top
     * @param int $left
     * @param array $params
     * @return $this
     */
    public function watermarkImage(string $image, int $top, int $left, array $params = []): self;

    /**
     * @see https://github.com/h2non/imaginary#get--post-blur
     * @param float $sigma
     * @param array $params
     * @return $this
     */
    public function blur(float $sigma, array $params = []): self;

    /**
     * @return ImaginaryResource
     */
    public function execute(): ImaginaryResource;

    /**
     * @see https://github.com/h2non/imaginary#get--post-info
     * @return array
     */
    public function info(): array;

    /**
     * @see https://github.com/h2non/imaginary#get-health
     * @return array
     */
    public function health(): array;
}
