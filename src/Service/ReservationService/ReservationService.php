<?php
namespace App\Service\ReservationService;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Service\ReservationDetailService\ReservationDetailService;
use App\Service\UserService\UserService;
use App\Service\LocalService\LocalService;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ReservationService  {
        private $reservationRepository;
        private $userService;
        private $userRepository;
        private $localService;


        public function __construct(ReservationRepository $reservationRepository , ReservationDetailService $reservationDetailService,LocalService $localService, UserService $userService, UserRepository $userRepository)
        {
            $this->reservationRepository = $reservationRepository;
            $this->userService = $userService;
            $this->reservationDetailService = $reservationDetailService;
            $this->userRepository=$userRepository;
            $this->localService = $localService;

            
          
        }

        public function add($data):int{
            $nbrEnfant = $data['resesrvationDetails'][0]['nbrEnfant'];
            $nbrAdulte = $data['resesrvationDetails'][0]['nbrAdulte'];
            $type = $data['type']['id'];
            $dateDebut = $data['resesrvationDetails'][0]['dateDebut'];
            $dateFin = $data['resesrvationDetails'][0]['dateFin'];
            $locaux = $this->localService->findByDisponibilite( $nbrEnfant, $nbrAdulte,$type,$dateFin ,$dateDebut);
            if($locaux != null){
            $membre = $this->userService->getByNumAdesion($data['membre']['numAdesion']);
            $total = 0.0;
            if( $membre == null){
                   return -1;
            }   
            else{
                $reservation = new Reservation();    
                $currentDate = strtotime(date('d M Y H:i:s'));  
                $reservation
                    ->setTotal($total)
                    ->setDateReservation($currentDate)
                    ->setMember($membre)
                    ->setEtat(0);
                $newReservation = $this->reservationRepository->saveReservation($reservation);
                if(!empty($data['resesrvationDetails'])){
                foreach ($data['resesrvationDetails'] as $item) {
                    $item['local']['id'] = $locaux[0]['id']; 
                    $result = $this->reservationDetailService->add($item, $newReservation);
                    if($result[0]['code'] == 1){
                        $total  += $result[0]['result']->getPrixCalcule();
                    }  
                }  
                    //update total
                    $newReservation->setTotal($total);
                    $this->update($newReservation->getId(), $total);
                
                }
            
            return 1;
            }
        }
        else{
            return -2;
        }

           
       }

        public function findById($id){
            $reservation = $this->reservationRepository->findOneBy(['id' => $id]);
            $serializer = $this->serialiser();
            $jsonContent = $serializer->serialize($reservation, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
            'dateReservation', 'total','member'=>['id','numAdesion'], 
            'resesrvationDetails' =>['id','dateDebut','dateFin','prixCalcule','local'=>['id','nom','type'=>['label']],'nbrAdulte','nbrEnfant']
            ]]);
            $result =  json_decode($jsonContent ,true);
            return $result;
        }
        public function serialiser(){
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            return $serializer;
        }
        //find all
        public function findAll():array{
            $reservations = $this->reservationRepository->findAll();
            $result = null;
            if(!empty($reservations)){
            $serializer = $this->serialiser();
            $jsonContent = $serializer->serialize($reservations, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
            'dateReservation', 'total','membre'=>['id','numAdesion'], 
            'resesrvationDetails' =>['id','dateDebut','dateFin','prixCalcule','local'=>['id']]
            ]]);
            $result =  json_decode($jsonContent ,true);
            }
            return $result;

        }

        public function delete($id):int{
            $reservation = $this->reservationRepository->findOneBy(['id' => $id]);
            if($reservation==null){
              return -1;
            }
            $this->reservationRepository->remove($reservation);
            return 1;
        }

        public function findByMembre($id){
            $reservations = $this->reservationRepository->findBy(
                ['member' => $id],
        
            );
            $serializer = $this->serialiser();
            $jsonContent = $serializer->serialize($reservations, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
            'dateReservation', 'total','etat','member'=>['id','numAdesion'], 
            'resesrvationDetails' =>['id','dateDebut','dateFin','prixCalcule','local'=>['id']]
            ]]);
            $result =  json_decode($jsonContent ,true);
            return $result;
        }
        public function findByEtat($etat){
            $reservations = $this->reservationRepository->findBy(
                ['etat' => $etat]
            );
            
            foreach ($reservations as $value){ 
                if($value->getMember()!=null){
                    $member=$this->userRepository->findOneBy(['id'=>$value->getMember()->getId()]);
                    $value->setMember($member);
                }
                
              } 
            $serializer = $this->serialiser();
            $jsonContent = $serializer->serialize($reservations, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
            'dateReservation', 'total','etat','member'=>['id','numAdesion'], 
            'resesrvationDetails' =>['id','dateDebut','dateFin','prixCalcule','local'=>['id']]
            ]]);
            $result =  json_decode($jsonContent ,true);
            return $result;
        }
        public function findByEtatAndType($etat,$typeLocal){
            $reservations =$this->findByEtat($etat);
            $reservationsByType=array();
            foreach ($reservations as $value){ 
                $local=$this->localService->findById($value['resesrvationDetails'][0]['local']['id']);
                $typeValue=$local[0]['type']['label'];
                if($typeValue==$typeLocal){
                    array_push($reservationsByType,$value);
                }
                
              } 
            $serializer = $this->serialiser();
            $jsonContent = $serializer->serialize($reservationsByType, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
            'dateReservation', 'total','etat','member'=>['id','numAdesion'], 
            'resesrvationDetails' =>['id','dateDebut','dateFin','prixCalcule','local'=>['id']]
            ]]);
            $result =  json_decode($jsonContent ,true);
            return $result;
        }
        
        public function update($id,$data){
            $reservation = $this->reservationRepository->findOneBy(['id' => $id]);
            if($reservation == null){
                return -2;
            }
            else{
            $reservation
                     ->setTotal($data);
            $this->reservationRepository->update($reservation);
            return 1;
        }
        }
        public function changeLocal($id,$data){
            $reservation = $this->reservationRepository->findOneBy(['id' => $id]);
            $local=$this->localService->getById($data["id"]);
            if($reservation == null){
                return -2;
            }
            else{
            $reservation
                     ->getResesrvationDetails()[0]->setLocal($local[0]);
            $this->reservationRepository->update($reservation);
            return 1;
        }
        }
        public function acceptReservation($id){
            $reservation = $this->reservationRepository->findOneBy(['id' => $id]);
            if($reservation == null){
                return -2;
            }
            else{
            $reservation
                     ->setEtat(1);
            $this->reservationRepository->update($reservation);
            return 1;
        }
        }
        public function denyReservation($id){
            $reservation = $this->reservationRepository->findOneBy(['id' => $id]);
            if($reservation == null){
                return -2;
            }
            else{
            $reservation
                     ->setEtat(2);
            $this->reservationRepository->update($reservation);
            return 1;
        }
        }
        public function annulerReservation($id){
            $reservation = $this->reservationRepository->findOneBy(['id' => $id]);
            if($reservation == null){
                return -2;
            }
            else{
            $reservation
                     ->setEtat(3);
            $this->reservationRepository->update($reservation);
            return 1;
        }
    }


}