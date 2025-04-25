<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Form\ZoneType;
use App\Repository\ZoneRepository;
use App\Repository\ObjetConnecteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion/zones')]
class GestionZoneController extends AbstractController
{
    #[Route('/', name: 'gestion_zone_index')]
    public function index(ZoneRepository $zoneRepository): Response
    {
        $zones = $zoneRepository->findAllWithObjectCount();
        
        return $this->render('gestion/zone/index.html.twig', [
            'zones' => $zones,
        ]);
    }
    
    #[Route('/new', name: 'gestion_zone_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $zone = new Zone();
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($zone);
            $entityManager->flush();
            
            $this->addFlash('success', 'La zone a été créée avec succès !');
            return $this->redirectToRoute('gestion_zone_index');
        }
        
        return $this->render('gestion/zone/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}', name: 'gestion_zone_show', methods: ['GET'])]
    public function show(Zone $zone): Response
    {
        return $this->render('gestion/zone/show.html.twig', [
            'zone' => $zone,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'gestion_zone_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Zone $zone, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'La zone a été modifiée avec succès !');
            return $this->redirectToRoute('gestion_zone_show', ['id' => $zone->getId()]);
        }
        
        return $this->render('gestion/zone/edit.html.twig', [
            'zone' => $zone,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}', name: 'gestion_zone_delete', methods: ['POST'])]
    public function delete(Request $request, Zone $zone, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$zone->getId(), $request->request->get('_token'))) {
            $entityManager->remove($zone);
            $entityManager->flush();
            $this->addFlash('success', 'La zone a été supprimée avec succès !');
        }
        
        return $this->redirectToRoute('gestion_zone_index');
    }
    
    #[Route('/{id}/objets', name: 'gestion_zone_objets')]
    public function zoneObjets(Zone $zone, ObjetConnecteRepository $objetConnecteRepository): Response
    {
        $objets = $objetConnecteRepository->findBy(['zone' => $zone]);
        
        return $this->render('gestion/zone/objets.html.twig', [
            'zone' => $zone,
            'objets' => $objets
        ]);
    }
    
    #[Route('/{id}/associer-objets', name: 'gestion_zone_associer_objets', methods: ['GET', 'POST'])]
    public function associerObjets(Request $request, Zone $zone, ObjetConnecteRepository $objetConnecteRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les objets qui ne sont pas encore associés à une zone
        $objetsSansZone = $objetConnecteRepository->findBy(['zone' => null]);
        
        // Traiter la soumission du formulaire
        if ($request->isMethod('POST')) {
            $objetsIds = $request->request->all('objets');
            
            if (!empty($objetsIds)) {
                $objets = $objetConnecteRepository->findBy(['id' => $objetsIds]);
                
                foreach ($objets as $objet) {
                    $objet->setZone($zone);
                }
                
                $entityManager->flush();
                $this->addFlash('success', count($objets) . ' objet(s) ont été associés à la zone avec succès !');
                return $this->redirectToRoute('gestion_zone_objets', ['id' => $zone->getId()]);
            }
        }
        
        return $this->render('gestion/zone/associer_objets.html.twig', [
            'zone' => $zone,
            'objets_sans_zone' => $objetsSansZone
        ]);
    }

    #[Route('/{id}/dissocier-objet/{objetId}', name: 'gestion_zone_dissocier_objet', methods: ['POST'])]
public function dissocierObjet(Request $request, Zone $zone, int $objetId, ObjetConnecteRepository $objetConnecteRepository, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('dissocier'.$objetId, $request->request->get('_token'))) {
        $objet = $objetConnecteRepository->find($objetId);
        
        if ($objet && $objet->getZone() === $zone) {
            $objet->setZone(null);
            $entityManager->flush();
            $this->addFlash('success', 'L\'objet a été dissocié de la zone avec succès !');
        }
    }
    
    return $this->redirectToRoute('gestion_zone_objets', ['id' => $zone->getId()]);
}
}