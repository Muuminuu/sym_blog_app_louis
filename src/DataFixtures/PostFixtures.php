<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;



class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void /// ici on a problème avec la classe AsciiSlugger
    {
        $slugger = new AsciiSlugger;

        $post = new Post();
        $post->setAuthor($this->getReference('user_1'));
        $post->setTitle('Lorem ipsum dolor sit amet');
        $post->setContent('content bla bla bla');
        $post->setPublished(true);
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setModifiedAt(new \DateTime());
        $post->setSlug($slugger->slug($post->getTitle()));
        $post->setImg($this->getReference('upload_file_1')); // ici à vérifier up_load_file ↔ upload_file_1
        
        $manager->persist($post);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            UploadFileFixtures::class
        ];
    }

}
