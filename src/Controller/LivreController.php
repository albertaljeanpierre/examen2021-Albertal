<?php

namespace App\Controller;

use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LivreType;

class LivreController extends AbstractController
{
    /**
     * @Route("/", name="livre")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {

        $entite = $entityManager->getRepository(Livre::class);
        $livres = $entite->findAll();

        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
        ]);
    }


    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail(EntityManagerInterface $entityManager, $id): Response
    {

        $entite = $entityManager->getRepository(Livre::class);
        $livre = $entite->find($id);

        return $this->render('livre/detail.html.twig', [
            'livre' => $livre,
            'id' => $id,
        ]);
    }

    /**
     * @Route("/livre/new", name="livreNew")
     */
    public function creationLivre(Request $request): Response
    {
        $livre = new Livre();

        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $livre = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livre);
            $entityManager->flush();
            return $this->redirectToRoute('livre');
        }


        return $this->renderForm('livre/ajout.html.twig', [
            'form' => $form,
        ]);
    }
}
