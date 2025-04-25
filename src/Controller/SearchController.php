<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search_results')]
    public function search(Request $request): Response
    {
        $query = trim($request->query->get('q')); // Récupérer la recherche textuelle

        // Récupérer les filtres (tableau vide si aucun filtre sélectionné)
        $tab = $request->query->all('tab');

        if (!is_array($tab)) {
            $tab = [];
        }

        return $this->render('search/results.html.twig', [
            'results' => $query,
            'filters' => $tab
        ]);
    }

    private function performSearch($query, $filters)
    {
        // Cette fonction devrait interroger la base de données ou une API selon tes critères
        // Par exemple, en fonction de la recherche et des filtres

        // Simulation de résultats en fonction de la recherche et des filtres
        return [
            'lieux' => ['Musée du Louvre', 'Parc des Buttes-Chaumont'],
            'evenements' => ['Concert à l’Opéra', 'Marché de Noël'],
            'transports' => ['Métro ligne 1', 'Bus 47'],
        ];
    }
}
