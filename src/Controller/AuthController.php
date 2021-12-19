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
        $password = $data['password'];
        $email = $data['email'];
        $phoneNumber=$data['phoneNumber'];
        $user = new User();
        $user->setPhoneNumber($phoneNumber);
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->json([
            'user' => $user->getEmail()
        ]);
    }
    /**
 * @Route("/auth/login", name="login", methods={"POST"})
 */
public function login(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
{
    $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy([
                'email'=>$data['email'],
        ]);
        if (!$user || !$encoder->isPasswordValid($user, $data['password'])) {
                return $this->json([
                    'message' => 'email or password is wrong.',
                ]);
        }
       $payload = [
           "user" => $data['email'],
           "exp"  => (new \DateTime())->modify("+5 minutes")->getTimestamp(),
       ];
        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return $this->json([
            'message' => 'success!',
            'token' => sprintf('Bearer %s', $jwt),
        ]);
}
}
