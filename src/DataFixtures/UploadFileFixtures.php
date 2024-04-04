<?php

namespace App\DataFixtures;

use App\Entity\UploadFile;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UploadFileFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $uploadFile = new UploadFile();
        $uploadFile->setImg('default.png');
        $uploadFile->setIsPrivate(false);
        $this->addReference('upload_file_1', $uploadFile);

        $manager->persist($uploadFile);

        $manager->flush();
    }
}
