<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\LocalService\LocalService;

class LocalController extends AbstractController
{
   
    private $localService;

    public function __construct(LocalService $localService )
    {
        $this->localService= $localService;
    }
    
    /**
     * @Route("/local", name="add_local", methods={"POST"})
     */
    /*public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if($this->localService->add($data)==1){
            return new JsonResponse(['status' => 'Local created!'], Response::HTTP_CREATED);
        }
    
        return new JsonResponse(['status' => 'Local created!'], Response::HTTP_CREATED);
    }
*/
    /**
         * @Route("/locals", name="get_all_locals", methods={"GET"})
    */
      public function getAll(): JsonResponse
     {
       
       $data = $this->localService->findAll();
       $response = new JsonResponse($data, Response::HTTP_OK);
        return $response;
     }
     /**
         * @Route("/locals/type/{type}", name="get_all_locals_type", methods={"GET"})
    */
    public function findByType($type): JsonResponse
    {
      
      $data = $this->localService->findByTypeLocal($type);
      $response = new JsonResponse($data, Response::HTTP_OK);
      return $response;
    }
     /**
         * @Route("/locals/type/{type}/nbrEnfant/{nbrEnfant}/nbrAdulte/{nbrAdulte}/{dateFin}/{dateDebut}", name="get_all_locals_disp", methods={"GET"})
    */
    public function findByDispo($type, $nbrEnfant,$nbrAdulte,$dateFin, $dateDebut): JsonResponse
    {
      
      $data = $this->localService->findByDisponibilite($nbrEnfant,$nbrAdulte,$type, $dateFin, $dateDebut);
      $response = new JsonResponse($data, Response::HTTP_OK);
      return $response;
    }
    
      /**
     * @Route("/local/id/{id}", name="get_local_by_id", methods={"GET"})
    */
     public function getById($id): JsonResponse
     {
        $data=$this->userService->getById($id);
        $response=new JsonResponse($data, Response::HTTP_OK);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
      }

       /**
        * @Route("/local/{id}", name="update_local", methods={"PUT"})
        */
       /* public function update($id, Request $request): JsonResponse
       {
         $data = json_decode($request->getContent(), true);
         $updatedLocal=$this->localService->update($id, $data);
         return new JsonResponse($updatedLocal, Response::HTTP_OK);
        }
*/
     /**
      * @Route("/local/{id}", name="delete_local", methods={"DELETE"})
     */
     public function delete($id): JsonResponse
     {
      
      if($this->localService->delete($id)==-1){
        $response=new JsonResponse(['status' => 'No Local with this id'], Response::HTTP_OK);
       $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
      }
      $response=new JsonResponse(['status' => 'Local deleted'], Response::HTTP_OK);
      $response->headers->set('Access-Control-Allow-Origin', '*');

      return $response;
     }


}
