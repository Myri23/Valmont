<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ObjetConnecte;
use App\Form\ObjetConnecteType;
use App\Form\ModifierProfilType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
* Contrôleur pour toutes les pages d'information de la ville
* 
* Ce contrôleur est marqué comme 'final' pour empêcher l'extension
* et gère une large variété de pages informatives sur la ville
*/
final class InformationController extends AbstractController
{
    private $client;
    private $apiKey;

   /**
    * Constructeur du contrôleur avec injection du client HTTP
    * 
    * @param HttpClientInterface $client Client HTTP pour les appels API
    */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
       /* Définition de la clé API pour OpenWeatherMap */        
        $this->apiKey = '117f375b15f90916c7cceaeb7095b905';
    }

   /**
    * Affiche la page d'accueil du portail d'information
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/information', name: 'app_information')]
    public function index(): Response
    {
        return $this->render('information/index.html.twig', [
            'controller_name' => 'InformationController',
        ]);
    }

   /**
    * Affiche les informations générales sur la ville
    * et récupère les données météorologiques actuelles
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/ville', name: 'ville')]
    public function ville(): Response
    {
        try {
           /* 
            * Appel à l'API OpenWeatherMap pour récupérer les données météo
            * Utilise Battambang comme source puis adapte pour Valmont
            */        
            $response = $this->client->request(
                'GET',
                'https://api.openweathermap.org/data/2.5/weather?q=Battambang&appid=' . $this->apiKey . '&units=metric&lang=fr'
            );

            $data = json_decode($response->getContent(), true);
            
            // Adaptation des données pour Valmont
            $weatherData = [
                'city' => 'Valmont',
                'temperature' => round($data['main']['temp']),
                'description' => $data['weather'][0]['description'],
                'humidity' => $data['main']['humidity'],
                'wind_speed' => round($data['wind']['speed'] * 3.6), // Conversion en km/h
                'icon' => $this->getWeatherIconClass($data['weather'][0]['icon'])
            ];

            return $this->render('information/ville.html.twig', [
                'weather' => $weatherData
            ]);
        } catch (\Exception $e) {
            return $this->render('information/ville.html.twig', [
                'weather' => null
            ]);
        }
    }

   /**
    * Convertit le code d'icône météo en classe CSS pour l'affichage
    * 
    * @param string $iconCode Code d'icône fourni par OpenWeatherMap
    * @return string Classe CSS correspondante pour Weather Icons
    */
    private function getWeatherIconClass($iconCode): string
    {
        $iconMap = [
            '01d' => 'wi-day-sunny',
            '01n' => 'wi-night-clear',
            '02d' => 'wi-day-cloudy',
            '02n' => 'wi-night-cloudy',
            '03d' => 'wi-cloud',
            '03n' => 'wi-cloud',
            '04d' => 'wi-cloudy',
            '04n' => 'wi-cloudy',
            '09d' => 'wi-showers',
            '09n' => 'wi-showers',
            '10d' => 'wi-day-rain',
            '10n' => 'wi-night-rain',
            '11d' => 'wi-thunderstorm',
            '11n' => 'wi-thunderstorm',
            '13d' => 'wi-snow',
            '13n' => 'wi-snow',
            '50d' => 'wi-fog',
            '50n' => 'wi-fog'
        ];

        return $iconMap[$iconCode] ?? 'wi-day-sunny';
    }

   /**
    * Affiche la page des lieux d'intérêt de la ville
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/lieux_interet', name: 'lieux_interet')]
    public function lieux_interet()
    {
        return $this->render('information/lieux_interet.html.twig');
    }

   /**
    * Affiche la page des événements de la ville
    * 
    * @return Response La réponse HTTP
    */    
    #[Route('/event', name: 'event')]
    public function event()
    {
        return $this->render('information/event.html.twig');
    }

   /**
    * Affiche les informations sur les lignes de bus
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/bus', name: 'bus')]
    public function bus()
    {
        return $this->render('information/bus.html.twig');
    }

   /**
    * Affiche les informations sur les lignes de tramway
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/tram', name: 'tram')]
    public function tram()
    {
        return $this->render('information/tram.html.twig');
    }

   /**
    * Affiche les informations sur les lignes de métro
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/metro', name: 'metro')]
    public function metro()
    {
        return $this->render('information/metro.html.twig');
    }

   /**
    * Affiche la page d'index des bibliothèques
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/bibliotheque', name: 'bibliotheque')]
    public function bibliotheque()
    {
        return $this->render('information/bibliotheque.html.twig');
    }

   /**
    * Affiche les informations sur la bibliothèque centrale
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/bibliotheque_centrale', name: 'bibliotheque_centrale')]
    public function bibliotheque_centrale()
    {
        return $this->render('information/bibliotheque_centrale.html.twig');
    }
    
   /**
    * Affiche les informations sur la bibliothèque jeunesse
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/bibliotheque_jeunesse', name: 'bibliotheque_jeunesse')]
    public function bibliotheque_jeunesse()
    {
        return $this->render('information/bibliotheque_jeunesse.html.twig');
    }

   /**
    * Affiche les informations sur la bibliothèque universitaire
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/bibliotheque_universite', name: 'bibliotheque_universite')]
    public function bibliotheque_universite()
    {
        return $this->render('information/bibliotheque_universite.html.twig');
    }

   /**
    * Affiche les informations sur le concert de jazz
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/concert_jazz', name: 'concert_jazz')]
    public function concert_jazz()
    {
        return $this->render('information/concert_jazz.html.twig');
    }

   /**
    * Affiche les informations sur le festival des talents
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/festival_talent', name: 'festival_talent')]
    public function festival_talent()
    {
        return $this->render('information/festival_talent.html.twig');
    }

   /**
    * Affiche la page d'index des musées
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/musees', name: 'musees')]
    public function musees()
    {
        return $this->render('information/musees.html.twig');
    }

   /**
    * Affiche les informations sur le musée d'histoire
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/musee_histoire', name: 'musee_histoire')]
    public function musee_histoire()
    {
        return $this->render('information/musee_histoire.html.twig');
    }

   /**
    * Affiche les informations sur le musée des sciences
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/musee_science', name: 'musee_science')]
    public function musee_science()
    {
        return $this->render('information/musee_science.html.twig');
    }

   /**
    * Affiche les informations sur le musée du citronnier
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/musee_citronnier', name: 'musee_citronnier')]
    public function musees_citronnier()
    {
        return $this->render('information/musee_citronnier.html.twig');
    }

   /**
    * Affiche la liste des objets connectés de la ville
    * 
    * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
    * @return Response La réponse HTTP
    */
    #[Route('/objets', name: 'objets')]
    public function objets(EntityManagerInterface $entityManager)
    {
       /* Récupération de tous les objets connectés depuis la base de données */
        $objets = $entityManager->getRepository(ObjetConnecte::class)->findAll();

       /* Affichage du template avec la liste des objets */
        return $this->render('information/objets.html.twig', [
        'objets' => $objets,  // Passer la variable 'objets' au template
        ]);
    }

   /**
    * Affiche la page sur les parcs et espaces verts
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/parcs', name: 'parcs')]
    public function parcs()
    {
        return $this->render('information/parcs.html.twig');
    }

   /**
    * Affiche la page d'index des restaurants
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/restaurants', name: 'restaurants')]
    public function restaurants()
    {
        return $this->render('information/restaurants.html.twig');
    }

   /**
    * Affiche les informations sur le restaurant Queen
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/restaurant_queen', name: 'restaurant_queen')]
    public function restaurant_queen()
    {
        return $this->render('information/queen.html.twig');
    }

   /**
    * Affiche les informations sur le restaurant Belle Époque
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/restaurant_belle_epoque', name: 'restaurant_belle_epoque')]
    public function restaurant_belle_epoque()
    {
        return $this->render('information/belle_epoque.html.twig');
    }

   /**
    * Affiche les informations sur le restaurant Petite Sirène
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/restaurant_petite_sirene', name: 'restaurant_petite_sirene')]
    public function restaurant_petite_sirene()
    {
        return $this->render('information/petite_sirene.html.twig');
    }

   /**
    * Affiche les informations sur l'exposition Tableaux Vivants
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/tableaux_vivants', name: 'tableaux_vivants')]
    public function tableaux_vivants()
    {
        return $this->render('information/tableaux_vivants.html.twig');
    }

   /**
    * Affiche les informations sur l'exposition Valmont
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/expo_valmont', name: 'expo_valmont')]
    public function expo_valmont()
    {
        return $this->render('information/expo_valmont.html.twig');
    }

   /**
    * Affiche les informations sur la chasse aux œufs
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/chasse_oeufs', name: 'chasse_oeufs')]
    public function chasse_oeufs()
    {
        return $this->render('information/chasse_oeufs.html.twig');
    }
}
