<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Entity\Categoria;

class UserController extends AbstractController
{   
    /**
     * @Route("/guardarUser", name="guardarUser")
     * @Method({"POST"})
     */
    public function guardarUser(UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine, Request $request)
    {
        $username = $request->request->get("username");
        $password = $request->request->get("password");

        $user = new User();
        $user->setUsername($username);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);      

        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $response = new Response(
            'Content',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
        $response->setContent('¡El usuario fue creado exitosamente!');
        return $response;
    }    

}