<?php

namespace Dridialaa\SyliusSiteParserPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use YourVendor\SyliusWebsiteParserBundle\Entity\Website;
use YourVendor\SyliusWebsiteParserBundle\Form\WebsiteType;
use YourVendor\SyliusWebsiteParserBundle\Parser\WebsiteParser;

class WebsiteController extends AbstractController
{
    public function index(Request $request, WebsiteParser $parser): Response
    {
        $website = new Website();
        $form = $this->createForm(WebsiteType::class, $website);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $parsedData = $parser->parse($website->getUrl());
            $website->setParsedData(json_encode($parsedData));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($website);
            $entityManager->flush();

            $this->addFlash('success', 'Website parsed and saved successfully!');
        }

        return $this->render('@DridiAlaSyliusSiteParserPlugin/website/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}