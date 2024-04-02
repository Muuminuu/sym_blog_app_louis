<?php

namespace App\Controller\User;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

#[Route('/profile/post')]
class UserPostController extends AbstractController
{
    #[Route('/', name: 'app_user_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $post = $postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy([
                'author' => $this->getUser(),
            ]),
        ]);
    }

    #[Route('/new', name: 'app_user_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user, SluggerInterface $slugger): Response
    {
        $post = new Post();
        $post->setAuthor($this->getUser());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('img')->getData();

            if ($imgFile) {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imgFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imgFilename' property to store the PDF file name
                // instead of its contents
                $post->setImg($newFilename);
            }
            $entityManager->persist($post);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_user_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /*
    * SLUGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG
    */
    #[Route('/{slug}', name: 'app_user_post_by_slug', methods: ['GET'])]
    public function showBySlug(PostRepository $postRepository, string $slug): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $postRepository->findOneBy(['slug' => $slug]),
        ]);
    }

    #[Route('/show/{id}', name: 'app_user_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_post_edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', 'post')]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('img')->getData();

            if ($imgFile) {
                $filesystem = new Filesystem();
                if($filesystem->exists('../public/uploads/img/' . $post->getImg())){
                    $filesystem->remove(['../public/uploads/img/' . $post->getImg()]);
                }
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imgFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imgFilename' property to store the PDF file name
                // instead of its contents
                $post->setImg($newFilename);
            }
            $entityManager->persist($post);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_user_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
