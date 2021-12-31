<?php

namespace App\Service\UserService;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserService{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        
    }
    public function findAll():array{
        $users = $this->userRepository->findAll();
        $data=[];
        $serializer = $this->serializer();
        $jsonContent = $serializer->serialize($users, 'json', [AbstractNormalizer::ATTRIBUTES => [
       'id','firstName','lastName','email','phoneNumber','numAdesion','roles']]);
       $result =  json_decode($jsonContent ,true);  
       return $result;

    }
    
    public function getById($id):array{
        $user = $this->userRepository->findOneBy(['id' => $id]);
        if($user==null){
            $data=[];
            return $data;
        }

        $serializer = $this->serializer();
        $jsonContent = $serializer->serialize($user, 'json', [AbstractNormalizer::ATTRIBUTES => [
       'id','firstName','lastName','email','phoneNumber','numAdesion','roles']]);
       $result =  json_decode($jsonContent ,true);  
        return $result;

    }

    
    public function getByAdesion($numAdesion): array{
        $user = $this->userRepository->findOneBy(['numAdesion' => $numAdesion]);
        if($user==null){
            $data=[];
            return $data;
        }
        $serializer = $this->serializer();
        $jsonContent = $serializer->serialize($user, 'json', [AbstractNormalizer::ATTRIBUTES => [
       'id','firstName','lastName','email','phoneNumber','numAdesion','roles']]);
       $result =  json_decode($jsonContent ,true);   
        return $result;
    }
    public function serializer(){
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer;
    }
    public function update($id,$data):array{
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $role=$data['role'];
        $user->setFirstName($data['firstName'])
        ->setLastName($data['lastName'])
        ->setEmail($data['email'])
        ->setPhoneNumber($data['phoneNumber'])
        ->setNumAdesion($data['numAdesion']);
        if($role!=null){
            $userRoles=$user->getRoles();
            array_push($userRoles,$role);
            $user->setRoles($userRoles);
        }
        $updatedUser = $this->userRepository->updateUser($user);
        $serializer = $this->serializer();
        $jsonContent = $serializer->serialize($updatedUser, 'json', [AbstractNormalizer::ATTRIBUTES => [
       'id','firstName','lastName','email','phoneNumber','numAdesion']]);
       $result =  json_decode($jsonContent ,true);   
        return $result;
    }
    public function delete($id):int{
        $user = $this->userRepository->findOneBy(['id' => $id]);
        if($user==null){
          return -1;
        }
  
       $this->userRepository->removeUser($user);
       return 1;
    }

}
