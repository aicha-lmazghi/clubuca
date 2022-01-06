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
     * @Route("tarif/local/{idLocal}", name="find_tarif", methods={"GET"})
     */
    public function findByLocal($idLocal): JsonResponse
    {
        $tarifs=$this->tarifService->getByLocal($idLocal);
        return new JsonResponse($tarifs, Response::HTTP_OK);    
    }
     /**
     * @Route("/tarif/id/{id}", name="update_tarif", methods={"PUT"})
     */
    public function update(Request $request,$id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $tarif=$this->tarifService->update($data,$id); 
    
        return new JsonResponse(['status' => 'Tarif updated'], Response::HTTP_OK);
    }
     /**
      * @Route("/tarif/id/{id}", name="delete_tarif", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
     
     if($this->tarifService->delete($id)==-1){
       $response=new JsonResponse(['status' => 'No Tarif with this id'], Response::HTTP_OK);
       $response->headers->set('Access-Control-Allow-Origin', '*');
       return $response;
     }
     $response=new JsonResponse(['status' => 'tarif deleted'], Response::HTTP_OK);
     $response->headers->set('Access-Control-Allow-Origin', '*');

     return $response;
    }

}