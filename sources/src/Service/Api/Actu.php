<?php

namespace App\Service\Api;

use App\Service\Api\Input\ActuInputDto;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Actu
{
    public function __construct(
        #[Autowire(param: 'API_ACTU')] private string     $apiKey,
        #[Autowire(param: 'API_ACTU_URL')] private string $apiActuUrl,
        private HttpClientInterface                       $httpClient,
    ){}

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function list(ActuInputDto $dto): array
    {
        $response = $this->httpClient->request(
            Request::METHOD_GET,
            $this->apiActuUrl. $dto->buildQuery($this->apiKey)
        );

        return $response->toArray();
    }
}