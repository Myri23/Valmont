<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use App\Repository\TransportRepository;
use App\Repository\EventRepository;
use App\Repository\ObjetConnecteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    private $lieuRepository;
    private $transportRepository;
    private $eventRepository;

    // Injecter le LieuRepository et TransportRepository dans le constructeur
    public function __construct(LieuRepository $lieuRepository, TransportRepository $transportRepository, EventRepository $eventRepository, ObjetConnecteRepository $objetRepository)
    {
        $this->lieuRepository = $lieuRepository;
        $this->transportRepository = $transportRepository;
        $this->eventRepository = $eventRepository;
        $this->objetRepository = $objetRepository;
    }

    #[Route('/search', name: 'search_results')]
    public function search(Request $request): Response
    {
        $query = trim($request->query->get('q')); // Récupérer la recherche textuelle

        // Récupérer les filtres (tableau vide si aucun filtre sélectionné)
        $tab = $request->query->all('tab');

        // Appeler la méthode searchLieux du LieuRepository
        $lieux = $this->lieuRepository->searchLieux($query);

        $transports = $this->transportRepository->searchTransport($query);

        $events = $this->eventRepository->searchEvent($query);

        return $this->render('search/results.html.twig', [
            'results' => $query,
            'filters' => $tab,
            'lieux' => $lieux,
            'transports' => $transports,
            'events' => $events
        ]);
    }

    #[Route('/searchObjects', name: 'search_results-objects')]
    public function search_objects(Request $request): Response
    {
        $queryObjects = trim($request->query->get('q-object')); // Récupérer la recherche textuelle

        // Récupérer les filtres (tableau vide si aucun filtre sélectionné)
        $tabObjects = $request->query->all('tab-object');

        dump($queryObjects);

        // Appeler la méthode searchObject du LieuRepository
        $objects = $this->objetRepository->searchObject($queryObjects);

        return $this->render('search/results-objects.html.twig', [
            'results' => $queryObjects,
            'filters' => $tabObjects,
            'objets' => $objects
        ]);
    }
}