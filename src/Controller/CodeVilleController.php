<?php

namespace App\Controller;

use App\Entity\CodeVille;
use App\Repository\CodeVilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/codes-ville')]
#[IsGranted('ROLE_ADMIN')]
class CodeVilleController extends AbstractController
{
    #[Route('/', name: 'admin_codes_ville_index')]
    public function index(CodeVilleRepository $codeVilleRepository): Response
    {
        $codes = $codeVilleRepository->findAll();
        $stats = $codeVilleRepository->getStatistiques();
        
        return $this->render('admin/codes_ville/index.html.twig', [
            'codes' => $codes,
            'stats' => $stats
        ]);
    }
    
    #[Route('/nouveau', name: 'admin_codes_ville_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $code = $request->request->get('code');
            $adresse = $request->request->get('adresse');
            $quartier = $request->request->get('quartier');
            
            if (!$code || !$adresse) {
                $this->addFlash('error', 'Le code et l\'adresse sont obligatoires');
                return $this->redirectToRoute('admin_codes_ville_new');
            }
            
            $codeVille = new CodeVille();
            $codeVille->setCode($code);
            $codeVille->setAdresse($adresse);
            $codeVille->setQuartier($quartier);
            $codeVille->setEstUtilise(false);
            
            $entityManager->persist($codeVille);
            $entityManager->flush();
            
            $this->addFlash('success', 'Le code a été créé avec succès');
            return $this->redirectToRoute('admin_codes_ville_index');
        }
        
        return $this->render('admin/codes_ville/new.html.twig');
    }
    
    #[Route('/generer-batch', name: 'admin_codes_ville_generate_batch')]
    public function generateBatch(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $quartier = $request->request->get('quartier');
            $adressePrefix = $request->request->get('adresse_prefix');
            $nombre = (int)$request->request->get('nombre', 1);
            $nombre = max(1, min(100, $nombre)); // Limiter entre 1 et 100
            
            if (!$adressePrefix) {
                $this->addFlash('error', 'Le préfixe d\'adresse est obligatoire');
                return $this->redirectToRoute('admin_codes_ville_generate_batch');
            }
            
            $codesGeneres = 0;
            
            for ($i = 1; $i <= $nombre; $i++) {
                // Générer un code unique de 8 caractères
                $code = strtoupper(substr($slugger->slug($quartier ?: 'VALMONT'), 0, 3) . bin2hex(random_bytes(3)));
                
                // Créer l'adresse avec un numéro séquentiel
                $adresse = $adressePrefix . ' ' . $i;
                
                $codeVille = new CodeVille();
                $codeVille->setCode($code);
                $codeVille->setAdresse($adresse);
                $codeVille->setQuartier($quartier);
                $codeVille->setEstUtilise(false);
                
                $entityManager->persist($codeVille);
                $codesGeneres++;
            }
            
            $entityManager->flush();
            
            $this->addFlash('success', "$codesGeneres codes ont été générés avec succès");
            return $this->redirectToRoute('admin_codes_ville_index');
        }
        
        return $this->render('admin/codes_ville/generate_batch.html.twig');
    }
    
    #[Route('/{id}/supprimer', name: 'admin_codes_ville_delete')]
    public function delete(CodeVille $codeVille, EntityManagerInterface $entityManager): Response
    {
        // Ne pas supprimer si déjà utilisé
        if ($codeVille->isEstUtilise()) {
            $this->addFlash('error', 'Impossible de supprimer un code déjà utilisé');
            return $this->redirectToRoute('admin_codes_ville_index');
        }
        
        $entityManager->remove($codeVille);
        $entityManager->flush();
        
        $this->addFlash('success', 'Le code a été supprimé avec succès');
        return $this->redirectToRoute('admin_codes_ville_index');
    }
    
    #[Route('/exporter', name: 'admin_codes_ville_export')]
    public function export(CodeVilleRepository $codeVilleRepository): Response
    {
        $codes = $codeVilleRepository->findBy(['est_utilise' => false]);
        
        return $this->render('admin/codes_ville/export.html.twig', [
            'codes' => $codes
        ]);
    }
}