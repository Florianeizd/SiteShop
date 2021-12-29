<?php

namespace App\Service\Imaginary;

use App\Exception\ImaginaryException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ImaginaryClient
 * @package App\Service
 */
class ImaginaryClient
{
    /**
     * @var string
     */
    public const HTTP_METHOD_GET = 'GET';
    public const HTTP_METHOD_POST = 'POST';

    private static string $serviceUri = '';
    private string $uploadFilePath = '';

    /**
     * @param string $serviceUri
     * @return $this
     */
    public function setServiceUri(string $serviceUri): self
    {
        self::$serviceUri = $serviceUri;

        return $this;
    }

    /**
     * @param string $uploadFilePath
     * @return $this
     */
    public function setUploadFilePath(string $uploadFilePath): self
    {
        $this->uploadFilePath = $uploadFilePath;

        return $this;
    }

    /**
     * @param string $httpMethod
     * @param string $method
     * @param array $params
     * @return ResponseInterface
     * @throws ImaginaryException
     * @throws TransportExceptionInterface
     */
    public function send(string $httpMethod, string $method, array $params = []): ResponseInterface
    {
        if (empty(self::$serviceUri)) {
            throw new ImaginaryException();
        }

        if ($httpMethod === self::HTTP_METHOD_POST) {
            $params = [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($this->uploadFilePath, 'rb'),
                    ],
                ],
                'query' => $params,
            ];
        }

        $client = HttpClient::create();
        $response = $client->request($httpMethod, self::$serviceUri.$method, $params);

        if ($response->getStatusCode() !== 200) {
            throw new ImaginaryException(sprintf('Service responded error %s', $response->getStatusCode()));
        }

        return $response;
    }
}
