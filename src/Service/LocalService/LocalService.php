<?php

namespace App\Service\LocalService;

use App\Entity\Local;
use App\Entity\Tarif;
use App\Entity\TypeLocal;
use App\Repository\LocalRepository;
use App\Repository\TarifRepository;
use App\Repository\TypeLocalRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use function PHPSTORM_META\type;

class LocalService{

    private $localRepository;
    private $typeRepository;
    private $tarifRepository;

    public function __construct(LocalRepository $localRepository, TypeLocalRepository $typeRepository, TarifRepository $tarifRepository)
    {
        $this->localRepository = $localRepository;
        $this->typeRepository=$typeRepository;
        $this->tarifRepository=$tarifRepository;
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

    public function add($data):int{
        $newLocal = new Local();

        $newLocal
            ->setNom($data['nom'])
            ->setDescription($data['description'])
            ->setAdresse($data['adresse'])
            ->setMaxAdulte($data['maxAdulte'])
            ->setMaxEnfant($data['maxEnfant']);
            $type=$data['type'];
            $typeLabel=$data['typeLabel'];
        
         if (empty($newLocal->getNom()) || empty($newLocal->getDescription()) || empty($newLocal->getAdresse()) ) {
                throw new NotFoundHttpException('Expecting mandatory parameters!');
            }
        $typeLocal=$this->typeRepository->findOneBy(['type' => $type]);
        if($typeLocal==null){
            $typeLocal=new TypeLocal();
            $typeLocal->setType($type);
            $typeLocal->setLabel($typeLabel);
            $this->typeRepository->save($typeLocal);
            $newLocal->setType($typeLocal);
        }else{
            $newLocal->setType($typeLocal);
        }
        $this->localRepository->saveLocal($newLocal);
        return 1;
    }
    
   
    public function update($id,$data):array{
        $local = $this->localRepository->findOneBy(['id' => $id]);
        $local ->setNom($data['nom'])
        ->setDescription($data['description'])
        ->setAdresse($data['adresse'])
        ->setMaxAdulte($data['maxAdulte'])
        ->setMaxEnfant($data['maxEnfant']);
        $typeLabel=$data['typeLabel'];
        $typeLocal=$this->typeRepository->findOneBy(['label' => $typeLabel]);
        if($typeLocal==null){
            $typeLocal=new TypeLocal();
            $typeLocal->setLabel($typeLabel);
            $this->typeRepository->save($typeLocal);
            $local->setType($typeLocal);
        }else{
            $local->setType($typeLocal);
        }
        $updatedLocal = $this->localRepository->updateLocal($local);
        $serializer = $this->serializer();
        $jsonContent = $serializer->serialize($updatedLocal, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
        'nom', 'description', 'adresse','maxEnfant','maxAdulte','tarif' =>['id','nbrEnfant','nbrAdulte','prix'], 'type'=>['id','label','type']
        ]]);
        $result =  json_decode($jsonContent ,true);               
        return $result ;
        
    }

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
        $serializer = $this->serializer();
        $jsonContent = $serializer->serialize($local, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
                'nom', 'description', 'adresse','maxEnfant','maxAdulte','tarif' =>['id','nbrEnfant','nbrAdulte','prix'], 'type'=>['id','label','type']
                ]]);
        $result =  json_decode($jsonContent ,true);       
        return $result ;
        }
        public function getById($id){
            if(empty($id)){
                return null;
            }
           $local = $this->localRepository->findBy(
                  ['id' => $id]
              );     
          return $local ;
          }
        public function addTarifs($id,$data){
            $local = $this->localRepository->findOneBy(['id' => $id]);
            $tarif=new Tarif();
            $tarif->setNbrAdulte($data["nbrAdulte"])
                ->setNbrEnfant($data["nbrEnfant"])
                ->setPrix($data["prix"]);
            $this->tarifRepository->save($tarif);
            $local->addTarif($tarif);
            $updatedLocal = $this->localRepository->updateLocal($local);
            $serializer = $this->serializer();
            $jsonContent = $serializer->serialize($updatedLocal, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
                    'nom', 'description', 'adresse','maxEnfant','maxAdulte','tarif' =>['id','nbrEnfant','nbrAdulte','prix'], 'type'=>['id','label','type']
                    ]]);
            $result =  json_decode($jsonContent ,true);       
            return $result ;
        }
}