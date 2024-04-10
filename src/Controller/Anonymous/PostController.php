<?php

namespace App\Controller\Anonymous;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(['published' => true]);

        return $this->render('user/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/{slug}', name: 'app_post_by_slug', methods: ['GET', 'POST'])]
    public function showBySlug(PostRepository $postRepository, string $slug, EntityManagerInterface $entityManager, Request $request, CommentRepository $commentRepository): Response
    {
        $post = $postRepository->findOneBy(['slug' => $slug, 'published' => true]);
        $comments = $commentRepository->findBy(['post' => $post]);
        $comment = new Comment();
        
        

        $comment->setUser($this->getUser());
        $comment->setPost($post);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_by_slug', ['slug' =>$slug]);
        }
        if (null != $post) {
            return $this->render('post/show.html.twig', [
                'post' => $post,
                'form' => $form,
                'comments' => $comments
            ]);
        } else {
            return $this->redirectToRoute('app_home');
        }  
        //
    }
    #[Route('/{id}/delete_comment_post', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        //dd($comment);
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
}