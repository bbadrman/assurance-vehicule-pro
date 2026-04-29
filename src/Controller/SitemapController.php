<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', defaults: ['_format' => 'xml'])]
    public function index(Request $request): Response
    {
        $baseUrl = $request->getSchemeAndHttpHost();

        $urls = [
            [
                'loc'        => $this->generateUrl('app_home', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => 'weekly',
                'priority'   => '1.0',
                'lastmod'    => '2026-04-22',
            ],
            [
                'loc'        => $this->generateUrl('app_utilitaire', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => 'weekly',
                'priority'   => '0.9',
                'lastmod'    => '2026-04-22',
            ],
            [
                'loc'        => $this->generateUrl('app_poidslourd', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => 'weekly',
                'priority'   => '0.9',
                'lastmod'    => '2026-04-22',
            ],
        ];

        $response = new Response(
            $this->renderView('sitemap/index.xml.twig', ['urls' => $urls]),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/xml; charset=UTF-8',
                'X-Robots-Tag' => 'noindex',
            ]
        );

        $response->setSharedMaxAge(3600);

        return $response;
    }
}
