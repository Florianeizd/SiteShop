<?php

namespace App\Service\Imaginary;

use Exception;
use RuntimeException;

/**
 * Class ImaginaryClient
 * @package App\Service
 */
class ImaginaryResource
{
    /**
     * @var bool|resource
     */
    private $resource;

    /**
     * ImaginaryResource constructor.
     * @param string $responseContent
     */
    public function __construct(string $responseContent)
    {
        $tmpFile = tmpfile();
        fwrite($tmpFile, $responseContent);
        $this->resource = $tmpFile;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function getResource(): bool
    {
        if (!is_resource($this->resource)) {
            throw new RuntimeException('An error occurred while processing the image. File not received');
        }

        return $this->resource;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return file_get_contents($this->getPathProcessedFile());
    }

    /**
     * @return string
     */
    public function getPathProcessedFile(): string
    {
        return stream_get_meta_data($this->resource)['uri'];
    }
}
