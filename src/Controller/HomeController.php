<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Producto;

class HomeController extends AbstractController
{
    /**
    * @Route("/", name="index")
    */
    public function listarProductos(ManagerRegistry $doctrine)
    {
        $productos = $doctrine->getRepository(Producto::class)->findBy(['estado' => 'PUBLICADO']);
        return $this->render('home.html.twig', ['productos' => $productos]);
    }

    /**
    * @Route("/producto/{id}", name="detalleProducto")
    */
    public function detallarProducto(ManagerRegistry $doctrine, $id)
    {
        $producto = $doctrine->getRepository(Producto::class)->find($id);
        return $this->render('producto/detallarProducto.html.twig', ['producto' => $producto]);
    }

}
