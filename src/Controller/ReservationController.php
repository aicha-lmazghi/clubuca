<?php

namespace App\Controller;

use App\Service\ReservationService\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReservationController extends AbstractController
{
   
    private $reservationService;

    public function __construct(ReservationService $reservationService )
    {
        $this->reservationService= $reservationService;
    }

    /**
     * @Route("/reservation", name="add_reservation", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {      
        $data = json_decode($request->getContent(), true);
        $result = $this->reservationService->add($data);
        if($result == -1){
            return new JsonResponse(['status' => 'User dosnt exist!'], Response::HTTP_FORBIDDEN);
        }
            return new JsonResponse(['status' => 'Reservation created!'], Response::HTTP_CREATED);
        
    }

    /**
     * @Route("/reservation", name="get_reservations", methods={"GET"})
     */
    public function findAll(): JsonResponse
    {  
        $data = $this->reservationService->findAll();
        $response = new JsonResponse($data, Response::HTTP_OK);
        return $response;
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
        * @Route("/reservation/idMembre/{id}", name="get_reservation_membre", methods={"GET"})
        */
        public function getAllByMembre($id): JsonResponse
       {
        
         $data = $this->reservationService->findByMembre($id);
         $response = new JsonResponse($data, Response::HTTP_OK);
         return $response;

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



}