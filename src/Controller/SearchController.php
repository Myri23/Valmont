<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    private $lieuRepository;

    // Injecter le LieuRepository dans le constructeur
    public function __construct(LieuRepository $lieuRepository)
    {
        $this->lieuRepository = $lieuRepository;
    }

    #[Route('/search', name: 'search_results')]
    public function search(Request $request): Response
    {
        $query = trim($request->query->get('q')); // Récupérer la recherche textuelle

        // Récupérer les filtres (tableau vide si aucun filtre sélectionné)
        $tab = $request->query->all('tab');

       // Appeler la méthode searchLieux du LieuRepository
        $lieux = $this->lieuRepository->searchLieux($query);

        return $this->render('search/results.html.twig', [
            'results' => $query,
            'filters' => $tab,
            'lieux' => $lieux
        ]);
    }
}