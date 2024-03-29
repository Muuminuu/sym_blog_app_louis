<?php

namespace App\Controller\User;

use App\Model\User\UserResetPassword;
use App\Form\User\UserResetPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/profile')]
class UserProfilController extends AbstractController
{
    #[Route('/', name: 'app_profil')]
    public function index(): Response
    {

        $user = $this->getUser();

        $rolesArray = $user->getRoles();
        $rolesString = implode(' ', array_map('ucwords', array_map('strtolower', $rolesArray)));

        $roles = $rolesString; // Affichera "Roles User"

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
            'roles' => $roles
        ]);
    }
    #[Route('/editpassword', name: 'app_profil_editpassword')]
    public function editPassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        //dd($request->request->get('old-password'));
        $resetPassword = new UserResetPassword();
        $form = $this->createForm(UserResetPasswordType::class, $resetPassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $param = $request->request->all();
            $oldPassword = $param['user_reset_password']['password'];
            // vérifier si le mot de passe fourni correspond à celui de la bdd
            if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash(
                    'warning',
                    'Old password is not valid!'
                );
                // sinon on redirige sur app_profil_editpassword
                $this->redirectToRoute('app_profil_editpassword', [], Response::HTTP_SEE_OTHER);
            } else {
                //dd($this->getUser()->getPassword());
                $newPassword = $param['user_reset_password']['new_password']['first'];
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $newPassword
                );
                // si oui ou enregistre le nouveau mot de passe
                $this->getUser()->setPassword($hashedPassword);
                // on enregistre l'utilisateur
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    'Password updated!'
                );
                return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('profil/editpassword.html.twig', [
            'user'=> $this->getUser(),
            'form' => $form
        ]);
        
        // if (isset($_POST['password']) && isset($_POST['new-password'])
        //     && isset($_POST['confirm-new-password']) 
        //     && !empty($_POST['password']) && !empty($_POST['new-password']) 
        //     && !empty($_POST['confirm-new-password']) 
        // )
        // { 
        //     dd('hey');
        //     $user = $this->getUser();
        //     $testPassword = $user->getPassword();
        //     var_dump($user);
        //     dd($testPassword);
        //     $password = $_POST['password'];
        //     $newPassword = $_POST['new-password'];
        //     $confirmNewPassword = $_POST['confirm-new-password'];
        //     if (password_verify($password, $testPassword)) {
        //         if ($newPassword == $confirmNewPassword) {
        //             $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        //             $this->getUser()->setPassword($newPassword);
        //             $this->getUser()->setIsVerified(true);
        //         }
        //         return $this->render('profil/editpassword.html.twig', [
        //             'user'=> $this->getUser()
        //         ]);
        //     } 
        // }
        
    }
}