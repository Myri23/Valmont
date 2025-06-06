<?php

namespace App\Controller;

use App\Entity\ObjetConnecte;
use App\Entity\PoubelleConnectee;
use App\Entity\ParkingIntelligent;
use App\Form\ObjetConnecteType;
use App\Form\PoubelleConnecteeType;
use App\Form\ParkingIntelligentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ObjetConnecteAdminController extends AbstractController
{
    #[Route('/objets_gestion', name: 'objets_gestion')]
public function gestionObjets(Request $request, EntityManagerInterface $entityManager): Response
{
    // Vérifier si l'utilisateur a les droits administrateur
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
    // Récupérer les filtres s'ils existent
    $type = $request->query->get('type');
    $etat = $request->query->get('etat');
    $localisation = $request->query->get('localisation');
    
    // Préparer la requête
    $qb = $entityManager->createQueryBuilder();
    $qb->select('o')
       ->from(ObjetConnecte::class, 'o');
    
    // Appliquer les filtres si définis
    if ($type) {
        $qb->andWhere('o.type = :type')
           ->setParameter('type', $type);
    }
    
    if ($etat) {
        $qb->andWhere('o.etat = :etat')
           ->setParameter('etat', $etat);
    }
    
    if ($localisation) {
        $qb->andWhere('o.localisation LIKE :localisation')
           ->setParameter('localisation', '%' . $localisation . '%');
    }
    
    // Récupérer les objets filtrés
    $objets = $qb->getQuery()->getResult();
    
    // Compter les objets par type pour l'onglet catégories
    $poubellesCount = $entityManager->createQueryBuilder()
        ->select('COUNT(o.id)')
        ->from(ObjetConnecte::class, 'o')
        ->where('o.type LIKE :type')
        ->setParameter('type', '%poubelle%')
        ->getQuery()
        ->getSingleScalarResult();
        
    $parkingsCount = $entityManager->createQueryBuilder()
        ->select('COUNT(o.id)')
        ->from(ObjetConnecte::class, 'o')
        ->where('o.type LIKE :type')
        ->setParameter('type', '%parking%')
        ->getQuery()
        ->getSingleScalarResult();
    
    return $this->render('admin/objets_gestion.html.twig', [
        'objets' => $objets,
        'poubelles_count' => $poubellesCount,
        'parkings_count' => $parkingsCount,
    ]);
}
  
    
    #[Route('/admin/objet/{id}/details', name: 'admin_objet_details')]
    public function detailsObjet(ObjetConnecte $objet, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les détails spécifiques selon le type d'objet
        $details = null;
        
        if ($objet->getType() === 'poubelle') {
            $details = $entityManager->getRepository(PoubelleConnectee::class)->findOneBy(['objet' => $objet]);
        } elseif ($objet->getType() === 'parking') {
            $details = $entityManager->getRepository(ParkingIntelligent::class)->findOneBy(['objet' => $objet]);
        }
        
        return $this->render('admin/objet_details.html.twig', [
            'objet' => $objet,
            'details' => $details,
        ]);
    }
    
   
    
    #[Route('/admin/objet/{id}/supprimer', name: 'admin_objet_supprimer')]
    public function supprimerObjet(ObjetConnecte $objet, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez si l'utilisateur a les droits administrateur
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // La cascade assure que les entités liées (poubelle ou parking) seront également supprimées
        // grâce à la configuration onDelete: 'CASCADE' dans les entités PoubelleConnectee et ParkingIntelligent
        $entityManager->remove($objet);
        $entityManager->flush();
        
        $this->addFlash('success', 'L\'objet a été supprimé avec succès.');
        
        return $this->redirectToRoute('objets_gestion');
    }
    


    
}