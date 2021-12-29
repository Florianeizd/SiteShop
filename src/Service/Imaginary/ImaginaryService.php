<?php

namespace App\Service\Imaginary;

use App\Exception\ImaginaryException;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ImaginaryService
 * @package App\Service
 */
class ImaginaryService implements ImaginaryServiceInterface
{
    private string $uploadFilePath = '';
    private array $operations = [];

    /**
     * @inheritDoc
     */
    #[Pure]
    public static function new(): self
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function setUploadFilePath(string $uploadFilePath): self
    {
        $this->uploadFilePath = $uploadFilePath;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addOperation(string $operation, array $params = []): self
    {
        $this->operations[] = [
            'operation' => $operation,
            'params' => $params
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function crop(int $width, int $height, array $params = []): self
    {
        $requiredParams = [
            'width' => $width,
            'height' => $height,
        ];

        $this->addOperation('crop', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function smartCrop(int $width, int $height, array $params = []): self
    {
        $requiredParams = [
            'width' => $width,
            'height' => $height,
        ];

        $this->addOperation('smartcrop', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function resize(int $width, int $height, array $params = []): self
    {
        $requiredParams = [
            'width' => $width,
            'height' => $height,
        ];

        $this->addOperation('resize', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function enlarge(int $width, int $height, array $params = []): self
    {
        $requiredParams = [
            'width' => $width,
            'height' => $height,
        ];

        $this->addOperation('enlarge', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function extract(int $top, int $left, int $areaWidth, int $areaHeight, array $params = []): self
    {
        $requiredParams = [
            'top' => $top,
            'left' => $left,
            'areawidth' => $areaWidth,
            'areaheight' => $areaHeight,
        ];

        $this->addOperation('extract', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function zoom(int $factor, array $params = []): self
    {
        $requiredParams = [
            'factor' => $factor,
        ];

        $this->addOperation('zoom', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function thumbnail(int $width, int $height, array $params = []): self
    {
        $requiredParams = [
            'width' => $width,
            'height' => $height,
        ];

        $this->addOperation('thumbnail', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fit(int $width, int $height, array $params = []): self
    {
        $requiredParams = [
            'width' => $width,
            'height' => $height,
        ];

        $this->addOperation('fit', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rotate(int $deg, array $params = []): self
    {
        $requiredParams = [
            'rotate' => $deg,
        ];

        $this->addOperation('rotate', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function flip(int $width, int $height, array $params = []): self
    {
        $requiredParams = [
            'width' => $width,
            'height' => $height,
        ];

        $this->addOperation('flip', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function flop(int $width, int $height, array $params = []): self
    {
        $requiredParams = [
            'width' => $width,
            'height' => $height,
        ];

        $this->addOperation('flop', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function convert(string $type, array $params = []): self
    {
        $requiredParams = [
            'type' => $type,
        ];

        $this->addOperation('convert', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function watermark(string $text, array $params = []): self
    {
        $requiredParams = [
            'text' => $text,
        ];

        $this->addOperation('watermark', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function watermarkImage(string $image, int $top, int $left, array $params = []): self
    {
        $requiredParams = [
            'image' => $image,
            'top' => $top,
            'left' => $left,
        ];

        $this->addOperation('watermarkImage', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function blur(float $sigma, array $params = []): self
    {
        $requiredParams = [
            'sigma' => $sigma,
        ];

        $this->addOperation('blur', array_merge($requiredParams, $params));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function execute(): ImaginaryResource
    {
        try {
            $responseImage = $this->sendQuery('pipeline')->getContent();
        } catch (ImaginaryException|TransportExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface|ClientExceptionInterface|JsonException $e) {
            throw new \RuntimeException();
        }

        return new ImaginaryResource($responseImage);
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function info(): array
    {
        try {
            $response = $this->sendQuery('info')->getContent();
        } catch (ImaginaryException|TransportExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface|ClientExceptionInterface|JsonException $e) {
            throw new \RuntimeException();
        }

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }


    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws ImaginaryException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function health(): array
    {
        $client = new ImaginaryClient();
        return $client->send(ImaginaryClient::HTTP_METHOD_GET, 'health')->toArray();
    }

    /**
     * @param string $method
     * @return ResponseInterface
     * @throws ImaginaryException
     * @throws JsonException
     * @throws TransportExceptionInterface
     */
    private function sendQuery(string $method): ResponseInterface
    {
        if (!is_file($this->uploadFilePath)) {
            throw new ImaginaryException();
        }

        $client = new ImaginaryClient();
        $client->setUploadFilePath($this->uploadFilePath);

        return $client->send(ImaginaryClient::HTTP_METHOD_POST, $method, ['operations' => json_encode($this->operations, JSON_THROW_ON_ERROR)]);
    }
}
