<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Firebase\JWT\JWT;


class AuthController extends AbstractController
{
    #[Route('/auth', name: 'auth')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AuthController.php',
        ]);
    }
    /**
     * @Route("/auth/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $phoneNumber = $data['phoneNumber'];
        $firstName=$data['firstName'];
        $lastName=$data['lastName'];
        $role=$data['role'];
        $user = new User();
        if($role=="ROLE_USER" && $data['numAdesion']!=null){
            $numAdesion= $data['numAdesion'];
            $user->setNumAdesion($numAdesion);
            $userRoles=$user->getRoles();
            $user->setRoles($userRoles);
        }
        if($role=="ROLE_ADMIN" && $data['password']!=null){
            $password= $data['password'];
            $user->setPassword($encoder->encodePassword($user, $password));
            $userRoles=$user->getRoles();
            array_push($userRoles,$role);
            $user->setRoles($userRoles);
        }
        $user->setPhoneNumber($phoneNumber);
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->json([
            'user' => $user->getEmail()
        ]);
    }
    /**
     * @Route("/auth/connect", name="connect", methods={"POST"})
     */
    public function connect(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy([
            'email' => $data['email'],
        ]);
        if (!$user || !$encoder->isPasswordValid($user, $data['password'])) {
            return $this->json([
                'message' => 'email or password is wrong.',
            ]);
        }
        $payload = [
            "user" => $data['email'],
            "exp"  => (new \DateTime())->modify("+720 minutes")->getTimestamp(),
        ];
        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return $this->json([
            'message' => 'success!',
            'token' => sprintf('Bearer %s', $jwt),
        ]);
    }
    /**
     * @Route("/auth/connect/cli", name="connect_client", methods={"POST"})
     */
    public function connectClient(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy([
            'numAdesion' => $data['adhesion'],
        ]);
        $payload = [
            "user" => $user->getEmail(),
            "exp"  => (new \DateTime())->modify("+720 minutes")->getTimestamp(),
        ];
        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return $this->json([
            'message' => 'success!',
            'token' => sprintf('Bearer %s', $jwt),
        ]);
    }
}
