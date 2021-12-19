<?php

namespace App\Service\UserService;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        
    }
    public function findAll():array{
        $users = $this->userRepository->findAll();
        $data=[];
        foreach ($users as $user) {
            $data[] = [
              'id' => $user->getId(),
              'firstName' => $user->getFirstName(),
              'lastName' => $user->getLastName(),
              'email' => $user->getEmail(),
              'phoneNumber' => $user->getPhoneNumber(),
              'numAdesion' => $user->getNumAdesion()
             ];
     }
     return $data;

    }
    
    public function getById($id):array{
        $user = $this->userRepository->findOneBy(['id' => $id]);
        if($user==null){
            $data=[];
            return $data;
        }

        $data = [
           'id' => $user->getId(),
           'firstName' => $user->getFirstName(),
           'lastName' => $user->getLastName(),
           'email' => $user->getEmail(),
           'phoneNumber' => $user->getPhoneNumber(),
           'numAdesion' => $user->getNumAdesion()
           ];
        return $data;

    }

    public function getByNumAdesion($numAdesion){
        $user = $this->userRepository->findOneBy(['numAdesion' => $numAdesion]);
        return $user;
    }
    public function getByAdesion($numAdesion): array{
        $user = $this->userRepository->findOneBy(['numAdesion' => $numAdesion]);
        if($user==null){
            $data=[];
            return $data;
        }

        $data = [
           'id' => $user->getId(),
           'firstName' => $user->getFirstName(),
           'lastName' => $user->getLastName(),
           'email' => $user->getEmail(),
           'phoneNumber' => $user->getPhoneNumber(),
           ];
        return $data;
    }
    public function update($id,$data):array{
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $user->setFirstName($data['firstName'])
        ->setLastName($data['lastName'])
        ->setEmail($data['email'])
        ->setPhoneNumber($data['phoneNumber'])
        ->setNumAdesion($data['numAdesion']);
        $updatedUser = $this->userRepository->updateUser($user);
        return $updatedUser->toArray();
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
