<?php
namespace App\Controller\Admin;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/types", name="admin_types_")
 * @package App\Controller\Admin
 */
class TypeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index(TypeRepository $typeRepository)
    {
        $types = $typeRepository->findAll();

        return $this->render('/admin/type/index.html.twig',[
            'types' => $types,
        ]);
    }


}