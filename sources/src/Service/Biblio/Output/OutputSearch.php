<?php




namespace App\Service\Biblio\Output;

use App\Entity\Biblio;

interface OutputSearch
{
    /**
     *@var Biblio[] $biblio 
     */
    public function setBiblios(array $biblios): self;

    public function getResponse();
}
