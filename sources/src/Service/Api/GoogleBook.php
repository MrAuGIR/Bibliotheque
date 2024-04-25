<?php

namespace App\Service\Api;

use App\Service\Api\Input\SearchInputDto;
use App\Service\Api\Model\ApiBook;
use App\Service\Api\Output\ApiBookCollection;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
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
        private SerializerInterface $serializer,
    ){}

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function list(SearchInputDto $dto): ApiBookCollection
    {
        $response = $this->httpClient->request(
            Request::METHOD_GET,
            $this->apiUrl. $dto->buildQuery($this->apiKey)
        );
        return $this->serializer->deserialize($response->getContent(), ApiBookCollection::class, 'json');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function get(string $id): ApiBook
    {
        $response = $this->httpClient->request(
            Request::METHOD_GET,
            $this->apiUrl.'/'.$id.'?apiKey='.$this->apiKey
        );
        return $this->serializer->deserialize($response->getContent(), ApiBook::class, 'json');
    }
}