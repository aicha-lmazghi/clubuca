<?php

namespace App\Service\LocalService;

use App\Repository\LocalRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
class LocalService{

    private $localRepository;

    public function __construct(LocalRepository $localRepository)
    {
        $this->localRepository = $localRepository;
        
    }
    public function findAll():array{
        $locaux = $this->localRepository->findAll();
        $serializer = $this->serializer();
                $jsonContent = $serializer->serialize($locaux, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
                'nom', 'description', 'adresse','maxEnfant','maxAdulte','tarif' =>['id','nbrEnfant','nbrAdulte','prix'], 'type'=>['id','label','type']
                ]]);
                $result =  json_decode($jsonContent ,true);               
                return $result ;
    }
    public function findByDisponibilite($nbrEnfant ,$nbrAdulte,$type, $dateFin , $dateDebut){
            
            $locaux = $this->localRepository->findLocalLibre($nbrEnfant, $nbrAdulte, $type, $dateFin, $dateDebut);       
            return $locaux; 
    }

   /* public function add($data):int{
        $newLocal = new Local();

        $newLocal
            ->setNom($data['Nom'])
            ->setDescription($data['Description'])
            ->setAdresse($data['Adresse'])
            ->setPrix($data['Prix']);
            $cap=$data['Capacite'];
            $type=$data['Type'];
        
         if (empty($newLocal->getNom()) || empty($newLocal->getDescription()) || empty($newLocal->getAdresse()) || empty($newLocal->getPrix())) {
                throw new NotFoundHttpException('Expecting mandatory parameters!');
            }
        $this->localRepository->saveLocal($newLocal);
        return 1;
    }
    public function getById($id):array{
        $local = $this->localRepository->findOneBy(['id' => $id]);
        if($local==null){
            $data=[];
            return $data;
        }

        $data = [
            'id' => $local->getId(),
              'Nom' => $local->getNom(),
              'Description' => $local->getDescription(),
              'Adresse' => $local->getAdresse(),
              'Prix' => $local->getPrix(),
              'Type' => $local->getType()
           ];
        return $data;

    }
   
    public function update($id,$data):array{
        $local = $this->localRepository->findOneBy(['id' => $id]);
        $local ->setNom($data['Nom'])
        ->setDescription($data['Description'])
        ->setAdresse($data['Adresse'])
        ->setPrix($data['Prix'])
        ->setCapacite($data['Capacite'])
        ->setType($data['Type']);
        $updatedLocal = $this->localRepository->updateLocal($local);
        return $updatedLocal->toArray();
    }
*/
       public function serializer(){
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            return $serializer;
        }
        //findByLocal
         public function findByTypeLocal($id){
                if(empty($id)){
                    return null;
                }
                $locaux = $this->localRepository->findBy(
                    ['type' => $id]
            
                );
                $serializer = $this->serializer();
                $jsonContent = $serializer->serialize($locaux, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
                'nom', 'description', 'adresse','maxEnfant','maxAdulte','tarif' =>['id','nbrEnfant','nbrAdulte','prix'], 'type'=>['id','label','type']
                ]]);
                $result =  json_decode($jsonContent ,true);               
                return $result ;
        }

         public function delete($id):int{
                $local = $this->localRepository->findOneBy(['id' => $id]);
                if($local==null){
                return -1;
                }
        
               $this->localRepository->removeLocal($local);
               return 1;
            }

        public function findById($id){
          if(empty($id)){
              return null;
          }
            $local = $this->localRepository->findBy(
                ['id' => $id]

            );           
            return $local ;
        }
}