<?php

namespace App\Controller;

use App\Entity\Writer;
use App\Repository\WriterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WriterController extends AbstractController
{
    
    /**
     * @Route("/writer/add/ajax/{label}", name="add_ajax_writer", methods={"POST"})
     */
    public function addWiterAjax(string $label,Request $request, WriterRepository $wr, EntityManagerInterface $em):Response
    {
        $writer = new Writer();

        $writer->setLastName(trim(strip_tags($label)));

        $em->persist($writer);
        $em->flush();

        $id = $writer->getId();

        return $this->json(['id' => $id],200);
    }
}
