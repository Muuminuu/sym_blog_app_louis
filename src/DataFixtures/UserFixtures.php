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
        $password = $this->hasher->hashPassword($user, 'azerty');
        $user->setUsername('admin');
        $user->setEmail('8l5oZ@example.com');
        $user->setRoles(['ROLE_EDITOR']);
        $user->setPassword($password);
        $user->setIsVerified(true);
        $user->setAvatar($this->getReference('upload_file_1'));

        $this->addReference('user_1', $user);
        $manager->persist($user);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UploadFileFixtures::class
        ];
    }
}
