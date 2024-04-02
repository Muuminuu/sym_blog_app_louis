<?php 

// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

// it is used to upload a file to the server 
// use Symfony\Component\Filesystem\Filesystem;

class FileUploader
{
    
    
    /**
    * @var ParameterBagInterface $params
    */
    private $params;

    /**
    * @var SluggerInterface $slugger
    */
    private $slugger;

    public function __construct(
        ParameterBagInterface $params,
        SluggerInterface $slugger
        
    ) 
    {
        $this->params = $params;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, string $target, string $fileName = null): string
    {
        $targetDirectory = $this->params->get($target);
        //dd($targetDirectory);
        if (null != $fileName) {
            $filesystem = new \Symfony\Component\Filesystem\Filesystem();
            $filesystem->remove([$targetDirectory . '/' . $fileName]);
        }
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory($targetDirectory), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $fileName;
    }

    public function getTargetDirectory($targetDirectory): string
    {
        return $targetDirectory;
    }
}

?>