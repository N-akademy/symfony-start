<?php

declare(strict_types=1);
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
