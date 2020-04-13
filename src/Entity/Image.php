<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Controller\Action\UploadImageAction;

/**
 * Class Image
 * @package App\Entity
 * @ORM\Entity()
 * @Vich\Uploadable()
 * @ApiResource(
 *      attributes={"order" = {"id": "DESC"}},
 *      collectionOperations = {
 *        "get",
 *        "post" = {
 *          "method" = "POST",
 *          "path" = "/uploads/images",
 *          "controller" = UploadImageAction::class,
 *          "defaults" = {"_api_receive" = false}
 *       }
 *    }
 * )
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="uploads", fileNameProperty="url")
     */
    private $file;

    /**
     * @ORM\Column(name="url", type="string", nullable=true)
     * @Groups({"get-blog-post-with-author"})
     */
    private $url;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return Image
     */
    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return '/uploads/images/' . $this->url;
    }

    /**
     * @param mixed $url
     * @return Image
     */
    public function setUrl($url): self
    {
        $this->url = $url;

        return $this;
    }
}
