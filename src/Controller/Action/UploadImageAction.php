<?php

namespace App\Controller\Action;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use App\Controller\AppController;
use App\Entity\Image;
use App\Form\ImageType;

/**
 * Class UploadImageAction
 * @package App\Controller\Action
 */
class UploadImageAction extends AppController
{
    /**
     * @return Image
     */
    public function __invoke()
    {
        // Create a new Image instance
        $image = new Image();

        // Validate the form
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new image entity
            $this->getManager()->persist($image);
            $this->getManager()->flush();

            $image->setFile(null);

            return $image;
        }

        // Uploading done for us in background by VichUploader

        // Throw an validation exception, that means something went wrong during
        // form validation
        throw new ValidationException($this->getValidator()->validate($image));
    }
}