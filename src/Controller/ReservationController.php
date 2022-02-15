<?php

namespace App\Controller;

use App\Service\ReservationService\ReservationService;
use App\Service\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReservationController extends AbstractController
{
   
    private $reservationService;
    private $userService;

    public function __construct(ReservationService $reservationService ,UserService $userService)
    {
        $this->reservationService= $reservationService;
        $this->userService=$userService;
    }

    /**
     * @Route("/reservation", name="add_reservation", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {      
        $data = json_decode($request->getContent(), true);
        $result = $this->reservationService->add($data);
        if($result == -1){
            return new JsonResponse(['status' => 'User dosnt exist!', 'code'=>-1], Response::HTTP_OK);
        }
        if($result == -2){
            return new JsonResponse(['status' => 'les locaux ne sont pas disponible !' ,'code'=>-2], Response::HTTP_OK);

        }
        if($result == 1)
            return new JsonResponse(['status' => 'Reservation created!', 'code'=> 1], Response::HTTP_CREATED);
        
    }

    /**
     * @Route("/reservations", name="get_reservations", methods={"GET"})
     */
   public function findAll(): JsonResponse
    {  
        $data = $this->reservationService->findAll();
        $response = new JsonResponse($data, Response::HTTP_OK);
        return $response;
    }
  /**
     * @Route("/reservation/id/{id}", name="find_reservation_by_id", methods={"GET"})
     */
    public function findById($id): JsonResponse
    {
        $result = $this->reservationService->findById($id);
        if($result ==null){
            return new JsonResponse(['status' => 'reservation dosnt exist!'], Response::HTTP_FORBIDDEN);
        }
            return new JsonResponse($result, Response::HTTP_OK);
    
    }
    /**
     * @Route("/reservation/id/{id}", name="delete_reservation", methods={"DELETE"})
     */
   public function delete($id): JsonResponse
    {
        $result = $this->reservationService->delete($id);
        if($result == -1){
            return new JsonResponse(['status' => 'reservation dosnt exist!'], Response::HTTP_FORBIDDEN);
        }
            return new JsonResponse(['status' => 'Reservation deleted!'], Response::HTTP_OK);
    
    }
    /**
        * @Route("/api/reservation/idMembre/{adesion}", name="get_reservation_membre", methods={"GET"})
        */
       public function getAllByMembre($adesion): JsonResponse
       {
        $user=$this->userService->getByNumAdesion($adesion);
         $data = $this->reservationService->findByMembre($user->getId());
         $response = new JsonResponse($data, Response::HTTP_OK);
         return $response;

        }
        /**
        * @Route("/reservation/etat/{etat}", name="get_reservation_etat", methods={"GET"})
        */
       public function getByEtat($etat): JsonResponse
       {
        
         $data = $this->reservationService->findByEtat($etat);
         $response = new JsonResponse($data, Response::HTTP_OK);
         return $response;

        }
         /**
        * @Route("/reservation/etat/{etat}/type/{type}", name="get_reservation_etat_type", methods={"GET"})
        */
       public function getByEtatAndType($etat,$type): JsonResponse
       {
        
         $data = $this->reservationService->findByEtatAndType($etat,$type);
         $response = new JsonResponse($data, Response::HTTP_OK);
         return $response;

        }
        /**
        * @Route("/reservation/accept/{id}", name="get_reservation_accept", methods={"PUT"})
        */
       public function accept($id): JsonResponse
       {
        
         $data = $this->reservationService->acceptReservation($id);
         
         if($data == -1){
            return new JsonResponse(['status' => 'reservation dosent exist!'], Response::HTTP_FORBIDDEN);
        }
            return new JsonResponse(['status' => 'Reservation accepted!'], Response::HTTP_OK);

        }
        /**
        * @Route("/reservation/deny/{id}", name="get_reservation_deny", methods={"PUT"})
        */
       public function deny($id): JsonResponse
       {
        
         $data = $this->reservationService->denyReservation($id);
         if($data == -1){
            return new JsonResponse(['status' => 'reservation dosnt exist!'], Response::HTTP_FORBIDDEN);
        }
            return new JsonResponse(['status' => 'Reservation denied!'], Response::HTTP_OK);

        }
         /**
        * @Route("/reservation/annuler/{id}", name="get_reservation_annuler", methods={"PUT"})
        */
       public function annuler($id): JsonResponse
       {
        
         $data = $this->reservationService->annulerReservation($id);
         if($data == -1){
            return new JsonResponse(['status' => 'reservation dosnt exist!'], Response::HTTP_FORBIDDEN);
        }
            return new JsonResponse(['status' => 'Reservation annuler!'], Response::HTTP_OK);

        }

      /**
        * @Route("/reservation/update/{id}", name="update_reservation", methods={"PUT"})
        */
        public function update($id, Request $request): JsonResponse
       {
         $data = json_decode($request->getContent(), true);
         $result=$this->reservationService->update($id, $data);

        if($result == -2){
            return new JsonResponse(['status' => 'nreservation dosnt exist!'], Response::HTTP_FORBIDDEN);
        }
        if($result == -1){
            return new JsonResponse(['status' => 'new User dosnt exist!'], Response::HTTP_FORBIDDEN);
        }
         return new JsonResponse(['status' => 'Reservation is updated !'], Response::HTTP_OK);
        }
        /**
        * @Route("/reservation/changeLocal/{id}", name="change_Local", methods={"PUT"})
        */
        public function changeLocal($id, Request $request): JsonResponse
       {
         $data = json_decode($request->getContent(), true);
         $result=$this->reservationService->changeLocal($id, $data);

        if($result == -2){
            return new JsonResponse(['status' => 'nreservation dosnt exist!'], Response::HTTP_FORBIDDEN);
        }
        if($result == -1){
            return new JsonResponse(['status' => 'new User dosnt exist!'], Response::HTTP_FORBIDDEN);
        }
         return new JsonResponse(['status' => 'Reservation is updated !'], Response::HTTP_OK);
        }
        



}