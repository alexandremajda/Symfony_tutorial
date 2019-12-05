<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\UploadImageAction;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use symfony\component\Validator\Constraints as Assert;

/**
 * _api_receive = false -> disable the automatic instance creation from request body 
 * 
 * @ORM\Entity()
 * @Vich\Uploadable()
 * @ApiResource(
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "method"="POST",
 *              "path"="/images",
 *              "controller"=UploadImageAction::class,
 *              "defaults"={"_api_receive"=false}
 *          }
 *      }
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
     * Specify a specific mapping defined by its name in the related configuration file
     * File Name Property symbolise the url where users can see uploaded files
     * 
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     * @Assert\NotBlank()
     */
    private $file;

    /**
     * @ORM\Column(nullable=true)
     * @Groups({"get-blog-post-with-author"})
     */
    private $url;


    public function getId()
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getUrl()
    {
        return '/images/'.$this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
}
