<?php

namespace App\Controller;

use App\Entity\SearchPost;
use App\Form\SearchPostType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([], ['id' => 'DESC'], 6);
        $searchPost = new SearchPost();
        $form = $this->createForm(SearchPostType::class, $searchPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $posts = $postRepository->findBySearch($searchPost);
        }

        return $this->render('home/index.html.twig', [
            'posts' => $posts ?? $postRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }
}
