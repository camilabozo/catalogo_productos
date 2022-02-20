<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Producto;
use App\Entity\Categoria;
use App\Entity\User;

/**
 * @Route("/gestion/productos")
 * @IsGranted("ROLE_ADMIN")
 */
class ProductoController extends AbstractController
{
    /**
    * @Route("/", name="gestionarProductos")
    */
    public function gestionarProductos(ManagerRegistry $doctrine)
    {
        $productos = $doctrine->getRepository(Producto::class)->findAll();
        return $this->render('/producto/gestionarProductos.html.twig', ['productos' => $productos]);
    }

    /**
    * @Route("/producto/nuevo", name="crearProducto")
    * @IsGranted("ROLE_ADMIN")
    */
    public function crearProducto(ManagerRegistry $doctrine)
    {
        $categorias = $doctrine->getRepository(Categoria::class)->findAll();
        return $this->render('producto/crearProducto.html.twig', ['categorias' => $categorias]);
    }

    /**
    * @Route("/producto/guardar", name="guardarProducto")
    * @Method({"POST"})
    */
    public function guardarProducto(ManagerRegistry $doctrine, Request $request)
    {
        $entityManager = $doctrine->getManager();
        $categoria_id = $request->request->get("categoria");
        $username = $request->request->get("username");
        $descripcion = $request->request->get("descripcion");
        $precio = $request->request->get("precio");
        $imagen = $request->request->get("imagen");
        $categoria = $doctrine->getRepository(Categoria::class)->find($categoria_id);
        $usuario = $doctrine->getRepository(User::class)->find(1);

        $producto = new Producto($categoria, $username, $descripcion, $precio, $imagen, $usuario);

        $entityManager->persist($producto);
        $entityManager->flush();
        return $this->redirectToRoute('crearProducto');
    }

    // /**
    // * @Route("/producto/{id}", name="detalleProducto")
    // */
    // public function detallarProducto(ManagerRegistry $doctrine, $id)
    // {
    //     $producto = $doctrine->getRepository(Producto::class)->find($id);
    //     return $this->render('producto/detallarProducto.html.twig', ['producto' => $producto]);
    // }
}