<?php

namespace App\DataFixtures;

use App\Entity\UploadFile;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UploadFileFixturesAvatar extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $array_img = array_slice(scandir('private\avatar\\'),2);
        shuffle($array_img);

        for ($i = 1; $i < 5; $i++) {
            $uploadFile = new UploadFile();
            $uploadFile->setImg($array_img[$i+1]);
            $uploadFile->setIsPrivate(false);
            $this->addReference('upload_file_avatar_'.$i, $uploadFile);

            $manager->persist($uploadFile);
        }
        $manager->flush();
    }
}
