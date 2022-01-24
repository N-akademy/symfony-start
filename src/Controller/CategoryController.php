<?php

declare(strict_types=1);
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_index")
     */
    public function index(): Response
    {
        return $this->render(
            'category/index.html.twig',
            ['categories' => $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll()]);
    }

    /**
     * The controller for the category add form
     *
     * @Route("/newcategory", name="category_new")
     */
    public function new(Request $request) : Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($category);
            // Flush the persisted object
            $entityManager->flush();
            // Finally, redirect to categorized list
            return $this->redirectToRoute('category_index');
        }
        // Render the form
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/category/{categoryName}", name="category_show")
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : '.$categoryName.' found in category table.');
        }

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);

        if (!$program) {
            echo('No program found in this category table.');
        }

        return $this->render('category/show.html.twig', [
            'programs' => $program, 'category' => $categoryName]);
    }
}
