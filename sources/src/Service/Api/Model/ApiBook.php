<?php

namespace App\Service\Api\Model;

class ApiBook
{
    protected string $id;

    protected string $etag;

    protected string $selfLink;

    protected ?VolumeInfo $volumeInfo;

    protected ?AccessInfo $accessInfo;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getEtag(): string
    {
        return $this->etag;
    }

    public function setEtag(string $etag): void
    {
        $this->etag = $etag;
    }

    public function getSelfLink(): string
    {
        return $this->selfLink;
    }

    public function setSelfLink(string $selfLink): void
    {
        $this->selfLink = $selfLink;
    }

    public function getVolumeInfo(): ?VolumeInfo
    {
        return $this->volumeInfo;
    }

    public function setVolumeInfo(?VolumeInfo $volumeInfo): void
    {
        $this->volumeInfo = $volumeInfo;
    }

    public function getAccessInfo(): ?AccessInfo
    {
        return $this->accessInfo;
    }

    public function setAccessInfo(?AccessInfo $accesInfo): void
    {
        $this->accessInfo = $accesInfo;
    }

    /**
     *@return string[]
     */
    public function getBookCategories(): array
    {
        if (empty($this->bookCategories)) {
            return ['unknown'];
        }
        $categoriesStr = $this->getVolumeInfo()->getCategories()[0] ?? 'unknown';
        return explode('/', $categoriesStr);
    }
}

