<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Form\LocalisationType;
use App\Repository\LocalisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/localisation')]
class LocalisationController extends AbstractController
{
    #[Route('/', name: 'app_localisation_index', methods: ['GET'])]
    public function index(LocalisationRepository $localisationRepository): Response
    {
        return $this->render('localisation/index.html.twig', [
            'localisations' => $localisationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_localisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LocalisationRepository $localisationRepository): Response
    {
        $location = new Localisation();
        $form = $this->createForm(LocalisationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localisationRepository->add($location);
            $this -> addFlash('success', "Localisation créée");
            return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localisation/new.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_localisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localisation $localisation, LocalisationRepository $localisationRepository): Response
    {
        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localisationRepository->add($localisation);
            $this -> addFlash('success', "Localisation modifiée");
            return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localisation/edit.html.twig', [
            'location' => $localisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localisation_delete', methods: ['POST'])]
    public function delete(Request $request, Localisation $localisation, LocalisationRepository $localisationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localisation->getId(), $request->request->get('_token'))) {
            $localisationRepository->remove($localisation);
            $this -> addFlash('success', "Localisation supprimée");
        }

        return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
    }
}
