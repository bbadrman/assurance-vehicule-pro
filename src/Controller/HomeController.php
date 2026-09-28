<?php
// assurance-vehicule-pro
namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

final class HomeController extends AbstractController
{
    public function __construct(
        #[Autowire(param: 'aksam_api.url')]
        private readonly string $aksamApiUrl,
        #[Autowire(param: 'aksam_api.token')]
        private readonly string $aksamApiToken,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        return $this->handleVehiculeForm($request, $entityManager, $logger, 'home/index.html.twig', 'app_home', '10');
    }

    #[Route('/utilitaire', name: 'app_utilitaire')]
    public function utilitaire(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        return $this->handleVehiculeForm($request, $entityManager, $logger, 'home/utilitaire.html.twig', 'app_utilitaire', '22');
    }

    #[Route('/poids-lourd', name: 'app_poidslourd')]
    public function poidslourd(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        return $this->handleVehiculeForm($request, $entityManager, $logger, 'home/poidslourd.html.twig', 'app_poidslourd', '23');
    }

    #[Route('/autocar', name: 'app_autocar')]
    public function autocar(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        return $this->handleVehiculeForm($request, $entityManager, $logger, 'home/autocar.html.twig', 'app_autocar', '24');
    }

    /**
     * Traite le formulaire de devis véhicule, commun aux 4 pages
     * (accueil, utilitaire, poids-lourd, autocar) — factorisé pour ne
     * garder qu'un seul endroit où le token API est utilisé.
     */
    private function handleVehiculeForm(
        Request $request,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        string $template,
        string $routeOnError,
        string $urlCode,
    ): Response {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($vehicule);
                $entityManager->flush();

                $this->addFlash('success', 'Votre demande de devis a bien été envoyée !');

                $telephone = $this->formatPhoneNumber($vehicule->getTele());
                $logger->info('Téléphone formaté:', [
                    'original' => $vehicule->getTele(),
                    'formaté' => $telephone,
                ]);

                $data = [
                    'nom' => $vehicule->getNom() ?? '',
                    'prenom' => $vehicule->getLastname() ?? '',
                    'phone' => $telephone ?? '',
                    'email' => $vehicule->getEmail() ?? '',
                    'raisonSociale' => $vehicule->getRaison() ?? '',
                    'typeProspect' => '2',
                    'source' => '3',
                    'activites' => '3',
                    'url' => $urlCode,
                ];

                $this->sendToAksamApi($data, $logger);

                return $this->redirectToRoute('app_reponse', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.');
                $logger->error('Erreur lors de l\'enregistrement du formulaire véhicule', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return $this->redirectToRoute($routeOnError, [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Envoie le prospect vers AksamCrm. Le token identifie ce site auprès
     * de l'API (détermine le produit côté serveur) — il vient
     * exclusivement de la configuration d'environnement
     * (AKSAM_API_TOKEN dans .env.local, jamais commité), pas du code.
     *
     * @param array<string, string> $data
     */
    private function sendToAksamApi(array $data, LoggerInterface $logger): void
    {
        $ch = curl_init($this->aksamApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-TOKEN: ' . $this->aksamApiToken,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $logger->info('Données envoyées à l\'API avec succès', [
                'status_code' => $httpCode,
                'response' => $response,
            ]);
        } else {
            $this->addFlash('warning', 'Votre demande a été enregistrée, mais un problème est survenu lors de la transmission.');
            $logger->error('Erreur lors de l\'envoi à l\'API', [
                'status_code' => $httpCode,
                'error' => $curlError,
                'response' => $response,
            ]);
        }
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
