<?php

namespace App\DataFixtures;

use App\Entity\UploadFile;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UploadFileFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 100; $i++) {
        $uploadFile = new UploadFile();
        $uploadFile->setImg('default.png');
        $uploadFile->setIsPrivate(false);
        $this->addReference('upload_file_'.$i, $uploadFile);

        $manager->persist($uploadFile);
        }
        $manager->flush();
    }
}
