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


        for ($i = 1; $i < 100; $i++) {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        $word = substr(implode($word), 0, 10);

        $content = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($content);
        $content = substr(implode($content), 0, 20);
        
        $post = new Post();
        $post->setAuthor($this->getReference('user_1'));
        $post->setTitle($word);
        $post->setContent($content);
        $post->setPublished(true);
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setModifiedAt(new \DateTime());
        $post->setSlug($slugger->slug($post->getTitle()));
        $post->setImg($this->getReference('upload_file_img'.$i)); // ici à vérifier up_load_file ↔ upload_file_1
        $manager->persist($post);

        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            UploadFileFixturesImage::class
        ];
    }

}
