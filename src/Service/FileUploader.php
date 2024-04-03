<?php 

// src/Service/FileUploader.php
namespace App\Service;

use App\Entity\UploadFile;
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

    /**
     * Method to upload a file
     * 
     * @param UploadedFile $file
     * @param string $target
     * @param bool $private
     * @param string $fileName
     * 
     * @return UploadFile $file
     */

    public function upload(UploadedFile $file, string $target, bool $private = true, string $fileName = null) : UploadFile
    {
        if ($private){
            $target = "private_" . $target;
        }
        $targetDirectory = $this->params->get($target);
        //dd($targetDirectory);
        if (null != $fileName) { // ca marche pas//////////////////////////////////////////////// à reprendre ici pour effacer /////////////////////// 
            $filesystem = new \Symfony\Component\Filesystem\Filesystem();
            $filesystem->remove([$targetDirectory . '/' . $fileName]);
        }

        

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($targetDirectory, $fileName);
            $fileUpload = new UploadFile();
            $fileUpload->setImg($fileName);
            $fileUpload -> setIsPrivate($private);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $fileUpload;
    }

    
}

?>