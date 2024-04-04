<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $password = $this->hasher->hashPassword($user, 'admin');
        $user->setUsername('admin');
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($password);
        $user->setIsVerified(true);
        $user->setAvatar($this->getReference('upload_file_avatar_1'));
        $this->addReference('user_1', $user);
        $manager->persist($user);

        $user = new User();
        $password = $this->hasher->hashPassword($user, 'editor');
        $user->setUsername('editor');
        $user->setEmail('editor@editor.com');
        $user->setRoles(['ROLE_EDITOR']);
        $user->setPassword($password);
        $user->setIsVerified(true);
        $user->setAvatar($this->getReference('upload_file_avatar_2'));
        $this->addReference('user_2', $user);
        $manager->persist($user);

        $user = new User();
        $password = $this->hasher->hashPassword($user, 'user');
        $user->setUsername('user');
        $user->setEmail('user@user.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($password);
        $user->setIsVerified(true);
        $user->setAvatar($this->getReference('upload_file_avatar_3'));
        $this->addReference('user_3', $user);
        $manager->persist($user);
        
        $user = new User();
        $password = $this->hasher->hashPassword($user, 'adam');
        $user->setUsername('leboss');
        $user->setEmail('leboss@leboss.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($password);
        $user->setIsVerified(true);
        $user->setAvatar($this->getReference('upload_file_avatar_4'));
        $this->addReference('user_4', $user);
        $manager->persist($user);
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UploadFileFixturesAvatar::class
        ];
    }
}
