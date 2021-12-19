<?php

namespace App\Service\TarifService;

use App\Entity\Tarif;
use App\Repository\TarifRepository;
use App\Service\LocalService\LocalService;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class TarifService{

    private $tarifRepository;
    private $localService;
    public function __construct(TarifRepository $tarifRepository, LocalService $localService)
    {
        $this->localService = $localService;
        $this->tarifRepository = $tarifRepository;
        
    }

    public function serializer(){
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer;
    }

    public function findAll():array{
        $tarifs = $this->tarifRepository->findAll();
        $serializer = $this->serializer();
        $jsonContent = $serializer->serialize($tarifs, 'json', [AbstractNormalizer::ATTRIBUTES => [
        'id','label']]);
                   $result =  json_decode($jsonContent ,true);               
                   return $result ;
   
   
       }
       public function add($data):int{
           $local =  $this->localService->findById($data['local']['id']);

           if($local == null){
               return -1;
           }
           $newTarif = new Tarif();
           $newTarif
                    ->setNbrAdulte($data['nbrAdulte'])
                    ->setNbrEnfant($data['nbrEnfant'])
                    ->setPrix($data['prix'])
                    ->setLocal($local[0]);
           $this->tarifRepository->save($newTarif);
           return 1;
       }
        public function findByLocal($idLocal){
        $tarifs = $this->tarifRepository->findBy(['local' => $idLocal]);
        $serializer = $this->serializer();
        $jsonContent = $serializer->serialize($tarifs, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
        'nbrAdulte', 'nbrEnfant', 'prix']]);
        $result =  json_decode($jsonContent ,true);               
        return $result ;

       }

}
