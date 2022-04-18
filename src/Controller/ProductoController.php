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

/**
 * @Route("/gestion/productos")
 * @IsGranted("ROLE_ADMIN")
 */
class ProductoController extends AbstractController
{
    /**
    * @Route("/", name="gestionarProductos")
    * @Method({"GET"})
    */
    public function gestionarProductos(ManagerRegistry $doctrine)
    {
        $productosPublicados = $doctrine->getRepository(Producto::class)->findBy(['estado' => 'PUBLICADO']);
        $productosPendientes = $doctrine->getRepository(Producto::class)->findBy(['estado' => 'PENDIENTE']);
        return $this->render('/producto/gestionarProductos.html.twig', ['productosPublicados' => $productosPublicados, 
                                                                        'productosPendientes' => $productosPendientes]);
    }

    /**
    * @Route("/nuevo", name="crearProducto")
    * @Method({"GET"})
    * @IsGranted("ROLE_ADMIN")
    */
    public function crearProducto(ManagerRegistry $doctrine)
    {
        $categorias = $doctrine->getRepository(Categoria::class)->findAll();
        return $this->render('producto/crearProducto.html.twig', ['categorias' => $categorias]);
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

        $usuario =  1; // $doctrine->getRepository(User::class)->find(1);//

        $producto = new Producto($categoria, $nombre, $descripcion, $precio, $imagen, $usuario);

        $entityManager->persist($producto);
        $entityManager->flush();
        return $this->redirectToRoute('crearProducto');
    }

    /**
    * @Route("/{id}", name="vistaPrevia")
    * @Method({"GET"})
    */
    // public function vistaPrevia(ManagerRegistry $doctrine, $id)
    // {
    //     $producto = $doctrine->getRepository(Producto::class)->find($id);
    //     return $this->render('producto/vistaPrevia.html.twig', ['producto' => $producto]);
    // }

     /**
     * @Route("/{id}", name="eliminarProducto")
     * @Method({"DELETE"})
     *
     */
    public function eliminarProducto(ManagerRegistry $doctrine, Producto $producto)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($producto);
        $entityManager->flush();
        return $this->redirectToRoute('gestionarProductos');
    }

    /**
    * @Route("/{id}/cambiarEstado", name="cambiarEstado")
    * @Method({"POST"})
    */
    public function cambiarEstado(ManagerRegistry $doctrine, Request $request, Producto $producto)
    {
        $entityManager = $doctrine->getManager();
        $estado = $request->query->get('estado');
        $producto->setEstado($estado);

        $entityManager->persist($producto);
        $entityManager->flush();
        return $this->redirectToRoute('gestionarProductos');
    }
}