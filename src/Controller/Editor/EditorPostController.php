<?php

namespace App\Controller\Editor;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditorPostController extends AbstractController
{
    #[Route('/editor/post', name: 'app_editor_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('editor_post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }
}
