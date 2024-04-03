<?php

namespace App\Controller;

use App\Entity\UploadFile;
use App\Entity\PrivatePdfExample;
use App\Entity\PrivateImageExample;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class ServePrivateFileController
 * @package App\Controller
 */
class ServePrivateFileController extends AbstractController
{
    /**
     * Returns a PrivateImageExample file for display.
     *
     * @param PrivateImageExample $privateImageExample
     * @Route("/serve-private-file/image/{name}", name="serve_private_image", methods="GET")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and user.getId() === privateImageExample.getUser().getId()")
     * @return BinaryFileResponse
     */
    
    public function privateImageExampleServe(UploadFile $privateImageExample): BinaryFileResponse
    {
        return $this->fileServe($privateImageExample->getPath());
    }
    /**
     * Returns a private file for display.
     *
     * @param string $path
     * @return BinaryFileResponse
     */

    #[Route("/private/avatar/{path}", name: "serve_private_image", methods: "GET")]
    public function fileServe(string $path): BinaryFileResponse
    {
        $absolutePath = $this->getParameter('private_avatar_directory') . '/' . $path;
        return new BinaryFileResponse($absolutePath);
    }
}