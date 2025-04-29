<?php

namespace App\Controller;

use App\Entity\ParkingIntelligent;
use App\Entity\ReservationParking;
use App\Form\ParkingIntelligentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/parking-intelligent')]
class ParkingIntelligentController extends AbstractController
{
    #[Route('/list', name: 'app_parking_intelligent_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les parkings intelligents
        $parkings = $entityManager->getRepository(ParkingIntelligent::class)->findAll();
        
        // Récupérer les réservations
        $reservations = $entityManager->getRepository(ReservationParking::class)->findBy([], ['dateReservation' => 'DESC']);
        
        return $this->render('parking_intelligent/list.html.twig', [
            'parkings' => $parkings,
            'reservations' => $reservations,
        ]);
    }
    
    #[Route('/reserver', name: 'app_parking_reserver_place', methods: ['POST'])]
    public function reserverPlace(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $parkingId = $data['parkingId'] ?? null;
        
        if (!$parkingId) {
            return new JsonResponse(['success' => false, 'message' => 'ID du parking manquant'], 400);
        }
        
        $parking = $entityManager->getRepository(ParkingIntelligent::class)->find($parkingId);
        
        if (!$parking) {
            return new JsonResponse(['success' => false, 'message' => 'Parking non trouvé'], 404);
        }
        
        // Vérifier si des places sont disponibles
        if ($parking->getPlacesDisponibles() <= 0) {
            return new JsonResponse(['success' => false, 'message' => 'Aucune place disponible'], 400);
        }
        
        // Créer une nouvelle réservation
        $reservation = new ReservationParking();
        $reservation->setParking($parking);
        
        // Utilisation du nom de l'utilisateur au lieu de l'entité User
        $username = $this->getUser() ? $this->getUser()->getUserIdentifier() : 'Anonyme';
        $reservation->setUtilisateurNom($username);
        
        $reservation->setDateReservation(new \DateTime());
        
        // Décrémenter le nombre de places disponibles
        $parking->setPlacesDisponibles($parking->getPlacesDisponibles() - 1);
        
        // Enregistrer les changements en base de données
        $entityManager->persist($reservation);
        $entityManager->flush();
        
        return new JsonResponse([
            'success' => true, 
            'message' => 'Place réservée avec succès',
            'reservationId' => $reservation->getId()
        ]);
    }
    
    #[Route('/annuler', name: 'app_parking_annuler_reservation', methods: ['POST'])]
    public function annulerReservation(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $reservationId = $data['reservationId'] ?? null;
        $parkingId = $data['parkingId'] ?? null;
        
        if (!$reservationId || !$parkingId) {
            return new JsonResponse(['success' => false, 'message' => 'Données manquantes'], 400);
        }
        
        $reservation = $entityManager->getRepository(ReservationParking::class)->find($reservationId);
        $parking = $entityManager->getRepository(ParkingIntelligent::class)->find($parkingId);
        
        if (!$reservation) {
            return new JsonResponse(['success' => false, 'message' => 'Réservation non trouvée'], 404);
        }
        
        if (!$parking) {
            return new JsonResponse(['success' => false, 'message' => 'Parking non trouvé'], 404);
        }
        
        // On n'a plus besoin de vérifier l'utilisateur puisqu'on stocke juste un nom
        
        // Incrémenter le nombre de places disponibles
        $parking->setPlacesDisponibles($parking->getPlacesDisponibles() + 1);
        
        // Supprimer la réservation
        $entityManager->remove($reservation);
        $entityManager->flush();
        
        return new JsonResponse([
            'success' => true, 
            'message' => 'Réservation annulée avec succès'
        ]);
    }
}
