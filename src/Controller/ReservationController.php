<?php
namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/concert_jazz', name: 'concert_jazz')]
public function reserver(Request $request, EntityManagerInterface $em): Response
{
    if ($request->isMethod('POST')) {
        $nom = $request->request->get('nom');
        $email = $request->request->get('email');
        $nombrePlacesDemandees = (int) $request->request->get('nb_places');

        // Compter le nombre de places déjà réservées
        $totalPlacesReservees = $em->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->select('SUM(r.nombrePlaces)')
            ->getQuery()
            ->getSingleScalarResult();

        // Sécurité : si pas de réservation encore faite, $totalPlacesReservees peut être NULL
        $totalPlacesReservees = $totalPlacesReservees ?? 0;

        if ($totalPlacesReservees + $nombrePlacesDemandees > 20) {
            return new Response('Désolé, il n\'y a plus assez de places disponibles. 😢', 400);
        }

        // Sinon on enregistre
        $reservation = new Reservation();
        $reservation->setNom($nom);
        $reservation->setEmail($email);
        $reservation->setNombrePlaces($nombrePlacesDemandees);

        $em->persist($reservation);
        $em->flush();

        return new Response('Réservation enregistrée ! 🎷', 200);
    }

    return $this->render('information/concert_jazz.html.twig');
}


}

