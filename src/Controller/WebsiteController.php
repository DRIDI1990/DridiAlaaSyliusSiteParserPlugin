<?php

namespace Dridialaa\SyliusSiteParserPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Dridialaa\SyliusSiteParserPlugin\Entity\Website;
use Dridialaa\SyliusSiteParserPlugin\Form\WebsiteType;
use Dridialaa\SyliusSiteParserPlugin\Parser\WebsiteParser;

class WebsiteController extends AbstractController
{
    /**
     * @Route("/admin/website-parser", name="admin_website_parser")
     */
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

        return $this->render('@DridialaaSyliusSiteParserPlugin/website/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
