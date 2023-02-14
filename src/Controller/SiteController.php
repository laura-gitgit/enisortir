<?php

namespace App\Controller;

use App\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
//    #[Route('/site', name: '_site')]
//    public function create(Request $request, EntityManagerInterface $em): Response
//    {
//        $site = new Site();
//        $siteForm = $this->createForm(SiteType::class);
//        $siteForm->handleRequest($request);
//
//        if($siteForm->isSubmitted() && $siteForm->isValid()){
//            $em->persist($site);
//            $em->flush();
//        }
//
//        return $this->render('site/create.html.twig', compact('siteForm'));
//    }
}
