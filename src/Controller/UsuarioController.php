<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Usuario;


class UsuarioController extends AbstractController
{
    /**
    * @Route("/usuario", name="usuario")
    */
    public function getUsuario(ManagerRegistry $doctrine)
    {
        $usuario = $doctrine->getRepository(Usuario::class)->find(1);
        dd($usuario);
        return $this->render('home.html.twig', ['usuario' => $usuario]);
    }
}