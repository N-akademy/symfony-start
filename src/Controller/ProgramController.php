<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController
{
    /**
     * @Route("/program", name="program_index")
     */
    public function index(): Response
    {
        return $this->render(
            'program/index.html.twig',
            ['programs' => $this->getDoctrine()
                ->getRepository(Program::class)
                ->findAll()]);
    }
    /**
     * The controller for the category add form
     *
     * @Route("/newprogram", name="program_new")
     */
    public function new(Request $request) : Response
    {
        // Create a new Category Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($program);
            // Flush the persisted object
            $entityManager->flush();
            // Finally, redirect to categorized list
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/program/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="program_show")
     */
    public function show(int $id): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        $seasons = $program->getSeasons();


        return $this->render('program/show.html.twig', [
            'program' => $program, 'seasons' => $seasons
        ]);
    }

    /**
     * @Route("/program/{programId}/seasons/{seasonId}", name="program_season_show")
     */
    public function showSeason(int $seasonId, int $programId ):Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $programId]);

        if (!$program) {
            throw $this->createNotFoundException(
                "No program with id : " . $programId . " found in program's table."
            );
        }

        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['program' => $program]);

        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }
}

