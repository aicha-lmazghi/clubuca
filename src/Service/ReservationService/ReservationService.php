<?php
namespace App\Service\ReservationService;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\LocalService\LocalService;
use App\Service\ReservationDetailService\ReservationDetailService;
use App\Service\UserService\UserService;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ReservationService  {
        private $reservationRepository;
        private $userService;
        private $localService;

        public function __construct(ReservationRepository $reservationRepository , ReservationDetailService $reservationDetailService,LocalService $localService, UserService $userService)
        {
            $this->reservationRepository = $reservationRepository;
            $this->userService = $userService;
            $this->reservationDetailService = $reservationDetailService;
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
                    ->setMembre($membre);
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
            return $reservation;
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
                ['membre' => $id],
        
            );
                return $reservations;
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


}