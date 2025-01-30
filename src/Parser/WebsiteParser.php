<?php

namespace Dridialaa\SyliusSiteParserPlugin\Service;

use YourVendor\SyliusWebsiteParserBundle\Entity\Website;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebsiteParserService
{
    private $httpClient;
    private $entityManager;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
    }

    public function parseWebsite(Website $website): void
    {
        $url = $website->getUrl();
        $response = $this->httpClient->request('GET', $url);
        $content = $response->getContent();

        // Exemple simple : extraire le titre de la page
        preg_match('/<title>(.*?)<\/title>/', $content, $matches);
        $title = $matches[1] ?? 'No title found';

        // Mettre à jour les données parsées
        $website->setParsedData(json_encode(['title' => $title]));
        $website->setUpdatedAt(new \DateTime());

        // Sauvegarder en base de données
        $this->entityManager->persist($website);
        $this->entityManager->flush();
    }
}