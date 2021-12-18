<?php

namespace App\Controller;

use App\Service\TarifService\TarifService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TarifController extends AbstractController
{
   
    private $tarifService;

    public function __construct(TarifService $tarifService )
    {
        $this->tarifService= $tarifService;
    }
    
    /**
     * @Route("/tarif", name="add_tarif", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if($this->tarifService->add($data) == 1){
            return new JsonResponse(['status' => 'Tarif created!'], Response::HTTP_CREATED);
        }
    
        return new JsonResponse(['status' => 'Tarif dosnt created!'], Response::HTTP_FORBIDDEN);
    }
    /**
     * @Route("/tarif/local/{idLocal}", name="add_tarif", methods={"GET"})
     */
    public function findByLocal($idLocal): JsonResponse
    {
        $tarifs=$this->tarifService->findByLocal($idLocal);
        return new JsonResponse($tarifs, Response::HTTP_OK);    
    }

}