<?php

namespace App\Service\Api;

use App\Service\Api\Input\SearchInputDto;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class GoogleBook
{
    public function __construct(
        #[Autowire(param: 'API_BOOK')]  private string     $apiKey,
        #[Autowire(param: 'API_BOOK_URL')]  private string $apiUrl,
        private HttpClientInterface                        $httpClient,
    ){}

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function list(SearchInputDto $dto): array
    {
        $response = $this->httpClient->request(
            Request::METHOD_GET,
            $this->apiUrl. $dto->buildQuery($this->apiKey)
        );

        return $response->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function get(int $id): array
    {
        $response = $this->httpClient->request(
            Request::METHOD_GET,
            $this->apiUrl.'/'.$id.'apiKey='.$this->apiKey
        );

        return $response->toArray();
    }
}