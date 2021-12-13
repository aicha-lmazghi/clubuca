<?php

namespace App\Controller;

use App\Service\ReservationDetailService\ReservationDetailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReservationDetailController extends AbstractController
{
   
    private $reservationDetailService;

    public function __construct(ReservationDetailService $reservationDetailService )
    {
        $this->reservationDetailService= $reservationDetailService;
    }
        /**
     * @Route("/reservationDetail", name="add_reservationDetail", methods={"POST"})
     */
    public function add(Request $request):JsonResponse
        {      
        $data = json_decode($request->getContent(), true);
        $result = $this->reservationDetailService->add($data,52);
        return new JsonResponse(['status' => 'Local created!'], Response::HTTP_CREATED);

    }


    /**
     * @Route("/reservationDetail/local/{local}", name="get_reservationDetail", methods={"GET"})
     */
    public function findByLocal($local): JsonResponse
    {
        $result = $this->reservationDetailService->findByLocal($local);
        $response = new JsonResponse($result , Response::HTTP_OK);
        return  $response;
    }

}
