<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Contrôleur gérant les données météo.
 */
class WeatherController extends AbstractController
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiKey = '117f375b15f90916c7cceaeb7095b905'; 
    }

    /**
     * Affiche les informations météorologiques.
     * 
     * @return Response
     */
    #[Route('/meteo', name: 'meteo')]
    public function index(): Response
    {
        try {
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
                'icon' => $data['weather'][0]['icon']
            ];

            return $this->render('information/ville.html.twig', [
                'weather' => $weatherData
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, rendu du template sans données météo        
            return $this->render('information/ville.html.twig', [
                'weather' => null
            ]);
        }
    }
} 
