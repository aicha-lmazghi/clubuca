<?php

namespace App\Service\TypeService;

use App\Entity\TypeLocal;
use App\Repository\TypeLocalRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class TypeService{

    private $typeRepository;

    public function __construct(TypeLocalRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
        
    }

    public function serializer(){
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer;
    }

    public function findAll():array{
     $types = $this->typeRepository->findAll();
     $serializer = $this->serializer();
     $jsonContent = $serializer->serialize($types, 'json', [AbstractNormalizer::ATTRIBUTES => [
     'id','label','type']]);
     $result =  json_decode($jsonContent ,true);               
     return $result ; 
    }

    public function add($data):int{
        $newType = new TypeLocal();

        $newType
               ->setLabel($data['label'])
               ->setType($data['type']);
        
         if (empty($newType->getLabel())) {
                throw new NotFoundHttpException('Expecting mandatory parameters!');
            }
        $this->typeRepository->save($newType);
        return 1;
    }   

    public function getById($id):array{
        $type = $this->typeRepository->findOneBy(['id' => $id]);
        if($type==null){
            $data=[];
            return $data;
        }
        $data = [
            'id' => $type->getId(),
            'label' => $type->getLabel()
           ];
        return $data;
    }
   
    /*public function update($id,$data):array{
        $type = $this->typeRepository->findOneBy(['id' => $id]);
        $type->setLabel($data['label']);
        $type->setType($data['type']);
        $updatedType = $this->typeRepository->update($type);
        return $updatedType->toArray();
    }*/
    public function delete($id):int{
        $type = $this->typeRepository->findOneBy(['id' => $id]);
        if($type==null){
          return -1;
        }
       $this->typeRepository->remove($type);
       return 1;
    }

}