<?php
namespace App\Service\ReservationDetailService;
use App\Entity\ResesrvationDetail;
use App\Repository\LocalRepository;
use App\Repository\ResesrvationDetailRepository;
use App\Service\TarifService\TarifService;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ReservationDetailService {
        private $reservationDetailRepository;
        private $localRepository;
        private $tarifService;

        public function __construct(ResesrvationDetailRepository $reservationDetailRepository, TarifService $tarifService, LocalRepository $localRepository)
        {
            $this->reservationDetailRepository = $reservationDetailRepository;
            /*$this->reservationService =$reservationService;*/
            $this->localRepository = $localRepository;
            $this->tarifService = $tarifService;
            
        }
        public function serializer(){
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            return $serializer;
        }
      
        //findByLocal
         public function findByLocal($local){
                if(empty($local)){
                    return null;
                }
          
                $reservationDetails = $this->reservationDetailRepository->findByExampleField($local);
              
                $serializer = $this->serializer();
                $jsonContent = $serializer->serialize($reservationDetails, 'json', [AbstractNormalizer::ATTRIBUTES => ['id',
                'dateDebut', 'dateFin','nbrEnfant','nbrAdulte', 'prixCalcule','reservation'=>['id'],'local'=>['id']
                ]]);
                $result =  json_decode($jsonContent ,true);              
                return $result ;
        }
        public function add($data, $reservation){
           
                $newReservationDetail = new ResesrvationDetail(); 
                //durée
                $dateDebut =  $data['dateDebut'];
                $dateFin = $data['dateFin'];                
                $diff = $dateFin - $dateDebut;
                $nbrJour =  round($diff / 86400);   
                $result = [];
                //find local 
                $local = $this->localRepository->findOneBy(['id' => $data['local']['id']]);
                //calcule prixcalcul
                if($local == null){
                    $result []= [
                        'code'=> -1,
                        'result' =>null
                    ];
                    }
                else{   
                $type = $local->getType()->getType();   
                $tarifs = $this->tarifService->findByLocal($local->getId());
                $prix=0;
                if($type == 1){
                    $prix = $tarifs[0]->getPrix();
                }   
                else if($type == 2){
                    foreach($tarifs as $tarif){
                        if($tarif->getNbrAdulte() == $data['nbrAdulte'] && $tarif->getNbrEnfant() == $data['nbrEnfant']){
                            $prix = $tarif->getPrix();
                            break;
                        }
                    }          
                }
                

                $prixCalcul =$prix * (float) $nbrJour;
                $newReservationDetail
                                    ->setDateDebut($dateDebut)
                                    ->setDateFin($dateFin)
                                    ->setPrixCalcule((float)$prixCalcul)
                                    ->setReservation($reservation)
                                    ->setNbrAdulte($data['nbrAdulte'])
                                    ->setNbrEnfant($data['nbrEnfant'])
                                    ->setLocal($local);
                                    
                 $reservationDetail =  $this->reservationDetailRepository->save($newReservationDetail);
                  $result []= [
                     'code' => 1,
                     'result' => $reservationDetail
                ];
           }
           return $result;
  
    
            }
           
           
        

     /*   public function findAll():array{
            $reservations = $this->reservationRepository->findAll();
            $data=[];
            foreach ($reservations as $reservation) {
                $data[] = [
                  'id' => $reservation->getId(),
                  'dateReservation' => $reservation->getDateReservation(),
                  'membre' => $reservation->getMembre()->getNumAdesion(),
                  'total' => $reservation->getTotal()
               
                 ];
         }
         return $data;
    
        }

        public function delete($id):int{
            $reservation = $this->reservationRepository->findOneBy(['id' => $id]);
            if($reservation==null){
              return -1;
            }
           $this->reservationRepository->remove($reservation);
           return 1;
        }
        public function update($id,$data){
            $reservation = $this->reservationRepository->findOneBy(['id' => $id]);
            $membre = $this->userService->getByNumAdesion($data['membre']['numAdesion']);
           
            if($reservation == null){
                return -2;
            }
            else{
                if( $membre == null){
                    return -1;
                }
            }
            $reservation
                    ->setTotal($data['total'])
                    ->setMembre($membre);
            $this->reservationRepository->update($reservation);
            return 1;
    }
*/

}