<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Producto;


class ProductoController extends AbstractController
{
    /**
    * @Route("/productos", name="productos")
    */
    public function listProductos(ManagerRegistry $doctrine)
    {
        $usuario = $doctrine->getRepository(Usuario::class)->find(1);
        return $this->render('home.html.twig', ['usuario' => $usuario]);
    }
}