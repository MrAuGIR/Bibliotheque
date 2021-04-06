<?php

namespace App\Controller;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{
    /**
     * @Route("/type/add/ajax/{label}", name="add_ajax_type", methods={"POST"})
     */
    public function addTypeAjax(string $label, Request $request, TypeRepository $wr, EntityManagerInterface $em): Response
    {
        $type = new Type();

        $type->setName(trim(strip_tags($label)));

        $em->persist($type);
        $em->flush();

        $id = $type->getId();

        return $this->json(['id' => $id], 200);
    }
}
