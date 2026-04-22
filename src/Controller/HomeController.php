<?php
// assurance-vehicule-pro
namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; 
use Psr\Log\LoggerInterface; 



final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
public function index(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
{
    $vehicule = new Vehicule();
    $form = $this->createForm(VehiculeType::class, $vehicule);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            // Sauvegarder en base de données
            $entityManager->persist($vehicule);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Votre demande de devis a bien été envoyée !');

            // 2. Formatage du téléphone
            $telephone = $this->formatPhoneNumber($vehicule->getTele());
            $logger->info('Téléphone formaté:', [
                'original' => $vehicule->getTele(),
                'formaté' => $telephone
            ]);

            // 3. Préparation des données pour l'API
            $data = [
                'nom'           => $vehicule->getNom() ?? '',
                'prenom'        => $vehicule->getLastname() ?? '',
                'phone'         => $telephone ?? '',
                'email'         => $vehicule->getEmail() ?? '', 
                'raisonSociale'  => $vehicule->getRaison() ?? '',
                
                'typeProspect'  => "2",
                'source'        => "3",
                'activites'     => "3",
                'url'           => "10",
                'product'       => '/api/products/1',
            ];

            // 4. Envoi via cURL
            $ch = curl_init('https://aksam.azurewebsites.net/api/prospects');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // 5. Vérification
            if ($httpCode >= 200 && $httpCode < 300) {
                $logger->info('Données envoyées à l\'API avec succès', [
                    'status_code' => $httpCode,
                    'response' => $response
                ]);
            } else {
                $this->addFlash('warning', 'Votre demande a été enregistrée, mais un problème est survenu lors de la transmission.');
                $logger->error('Erreur lors de l\'envoi à l\'API', [
                    'status_code' => $httpCode,
                    'error' => $curlError,
                    'response' => $response
                ]);
            }

            return $this->redirectToRoute('app_reponse', [], Response::HTTP_SEE_OTHER);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.');
            $logger->error('Erreur lors de l\'enregistrement du formulaire véhicule', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    return $this->render('home/index.html.twig', [
        'form' => $form->createView(),
    ]);
}
   

    #[Route('/utilitaire', name: 'app_utilitaire')]
    public function utilitaire(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {

         $vehicule = new Vehicule();
    $form = $this->createForm(VehiculeType::class, $vehicule);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            // Sauvegarder en base de données
            $entityManager->persist($vehicule);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Votre demande de devis a bien été envoyée !');

            // 2. Formatage du téléphone
            $telephone = $this->formatPhoneNumber($vehicule->getTele());
            $logger->info('Téléphone formaté:', [
                'original' => $vehicule->getTele(),
                'formaté' => $telephone
            ]);

            // 3. Préparation des données pour l'API
            $data = [
                'nom'           => $vehicule->getNom() ?? '',
                'prenom'        => $vehicule->getLastname() ?? '',
                'phone'         => $telephone ?? '',
                'email'         => $vehicule->getEmail() ?? '', 
                'raisonSociale'  => $vehicule->getRaison() ?? '',
                'typeProspect'  => "2",
                'source'        => "3",
                'activites'     => "3",
                'url'           => "22",
                'product'       => '/api/products/1',
            ];

            // 4. Envoi via cURL
            $ch = curl_init('https://aksam.azurewebsites.net/api/prospects');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // 5. Vérification
            if ($httpCode >= 200 && $httpCode < 300) {
                $logger->info('Données envoyées à l\'API avec succès', [
                    'status_code' => $httpCode,
                    'response' => $response
                ]);
            } else {
                $this->addFlash('warning', 'Votre demande a été enregistrée, mais un problème est survenu lors de la transmission.');
                $logger->error('Erreur lors de l\'envoi à l\'API', [
                    'status_code' => $httpCode,
                    'error' => $curlError,
                    'response' => $response
                ]);
            }

            return $this->redirectToRoute('app_reponse', [], Response::HTTP_SEE_OTHER);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.');
            $logger->error('Erreur lors de l\'enregistrement du formulaire véhicule', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
        return $this->render('home/utilitaire.html.twig', [
             'form' => $form->createView(),
           
        ]);
    }



     #[Route('/poids-lourd', name: 'app_poidslourd')]
    public function poidslourd(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {

          $vehicule = new Vehicule();
    $form = $this->createForm(VehiculeType::class, $vehicule);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            // Sauvegarder en base de données
            $entityManager->persist($vehicule);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Votre demande de devis a bien été envoyée !');

            // 2. Formatage du téléphone
            $telephone = $this->formatPhoneNumber($vehicule->getTele());
            $logger->info('Téléphone formaté:', [
                'original' => $vehicule->getTele(),
                'formaté' => $telephone
            ]);

            // 3. Préparation des données pour l'API
            $data = [
                'nom'           => $vehicule->getNom() ?? '',
                'prenom'        => $vehicule->getLastname() ?? '',
                'phone'         => $telephone ?? '',
                'email'         => $vehicule->getEmail() ?? '', 
                'raisonSociale'  => $vehicule->getRaison() ?? '',
                'typeProspect'  => "2",
                'source'        => "3",
                'activites'     => "3",
                'url'           => "23",
                'product'       => '/api/products/1',
            ];

            // 4. Envoi via cURL
            $ch = curl_init('https://aksam.azurewebsites.net/api/prospects');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // 5. Vérification
            if ($httpCode >= 200 && $httpCode < 300) {
                $logger->info('Données envoyées à l\'API avec succès', [
                    'status_code' => $httpCode,
                    'response' => $response
                ]);
            } else {
                $this->addFlash('warning', 'Votre demande a été enregistrée, mais un problème est survenu lors de la transmission.');
                $logger->error('Erreur lors de l\'envoi à l\'API', [
                    'status_code' => $httpCode,
                    'error' => $curlError,
                    'response' => $response
                ]);
            }

            return $this->redirectToRoute('app_reponse', [], Response::HTTP_SEE_OTHER);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.');
            $logger->error('Erreur lors de l\'enregistrement du formulaire véhicule', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
        return $this->render('home/poidslourd.html.twig', [
           'form' => $form->createView(),
        ]);
    }


    /**
     * Formatage du numéro de téléphone
     */
    private function formatPhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }
        if (str_starts_with($phone, '0')) {
            return '+33' . substr($phone, 1);
        }
        return $phone;
    }

     #[Route('/assurance-reponse', name: 'app_reponse')]
    public function reponse(): Response
    {
        return $this->render('home/reponse.html.twig', [
           
        ]);
    }

     #[Route('/politique', name: 'app_politique')]
    public function politique(): Response
    {
        return $this->render('home/politique.html.twig', [
           
        ]);
    }

     #[Route('/mention-legale', name: 'app_mention')]
    public function mention(): Response
    {
        return $this->render('home/mentions-legales.html.twig', [
           
        ]);
    }
}
