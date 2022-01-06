<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\TypeService\TypeService;

class TypeController extends AbstractController
{
   
    private $typeService;

    public function __construct(TypeService $typeService )
    {
        $this->typeService= $typeService;
    }
    
    /**
     * @Route("/type", name="add_type", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if($this->typeService->add($data)==1){
            return new JsonResponse(['status' => 'Type created!'], Response::HTTP_CREATED);
        }
    
        return new JsonResponse(['status' => 'Type created!'], Response::HTTP_CREATED);
    }

    /**
         * @Route("/types", name="get_all_types", methods={"GET"})
    */
      public function getAll(): JsonResponse
     {
       
       $data = $this->typeService->findAll();
       $response = new JsonResponse($data, Response::HTTP_OK);
       $response->headers->set('Access-Control-Allow-Origin', '*');
       return $response;
     }

    
      /**
     * @Route("/type/id/{id}", name="get_type_by_id", methods={"GET"})
    */
     public function getById($id): JsonResponse
     {
        $data=$this->typeService->getById($id);
        $response=new JsonResponse($data, Response::HTTP_OK);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
      }

       /**
        * @Route("/type/{id}", name="update_type", methods={"PUT"})
        */
        public function update($id, Request $request): JsonResponse
       {
         $data = json_decode($request->getContent(), true);
         $updatedType=$this->typeService->update($id, $data);
         return new JsonResponse($updatedType, Response::HTTP_OK);
        }

     /**
      * @Route("/type/{id}", name="delete_type", methods={"DELETE"})
     */
     public function delete($id): JsonResponse
     {
      
      if($this->typeService->delete($id)==-1){
        $response=new JsonResponse(['status' => 'No Type with this id'], Response::HTTP_OK);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
      }
      $response=new JsonResponse(['status' => 'Type deleted'], Response::HTTP_OK);
      $response->headers->set('Access-Control-Allow-Origin', '*');

      return $response;
     }


}