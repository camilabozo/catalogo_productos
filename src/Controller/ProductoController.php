<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Producto;
use App\Entity\Categoria;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
* @Route("/productos")
*/

class ProductoController extends AbstractController
{
    /**
    * @Route("/listar", name="listarProductos")
    */
    public function listProductos(ManagerRegistry $doctrine)
    {
        $productos = $doctrine->getRepository(Producto::class)->findAll();
        return $this->render('home.html.twig', ['productos' => $productos]);
    }

    /**
    * @Route("/producto/{id}", name="producto")
    */
    public function detailProducto(ManagerRegistry $doctrine, $id)
    {
        $producto = $doctrine->getRepository(Producto::class)->find($id);
        return $this->render('productoDetalle.html.twig', ['producto' => $producto]);
    }

    /**
    * @Route("/nuevo", name="nuevoProducto")
    */
    public function agregarProducto(ManagerRegistry $doctrine)
    {
        $categorias = $doctrine->getRepository(Categoria::class)->findAll();
        return $this->render('agregarProducto.html.twig', ['categorias' => $categorias]);
    }

    /**
    * @Route("/guardar", name="guardarProducto")
     * @Method({"POST"})
    */
    public function guardarProducto(ManagerRegistry $doctrine, Request $request)
    {
        $entityManager = $doctrine->getManager();
        $categoria_id = $request->request->get("categoria");
        $nombre = $request->request->get("nombre");
        $descripcion = $request->request->get("descripcion");
        $precio = $request->request->get("precio");
        $imagen = $request->request->get("imagen");
        $categoria = $doctrine->getRepository(Categoria::class)->find($categoria_id);
        $usuario = $doctrine->getRepository(Usuario::class)->find(1);

        $producto = new Producto($categoria, $nombre, $descripcion, $precio, $imagen, $usuario);

        $entityManager->persist($producto);
        $entityManager->flush();
        $categorias = $doctrine->getRepository(Categoria::class)->findAll();

        return $this->render('agregarProducto.html.twig', ['categorias' => $categorias]);
    }
}