<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\AuthorEntityInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\PublishedDateEntityInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The operation "api_*_get_subresource" make that the subresource appears like a list of objects 
 * instead of the related route of those objects 
 * 
 * With newer symfony version, the subresourceOperations is needed to call the normalizations of subresources instead of collectionOperations
 * 
 * @ApiResource(
 *      itemOperations={
 *          "get",
 *          "put"={
 *              "access_control"="is_granted('ROLE_EDITOR') or is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user"
 *          }
 *      },
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "access_control"="is_granted('ROLE_COMMENTATOR')"
 *          }
 *      },
 *      subresourceOperations={
 *          "api_blog_posts_comments_get_subresource"={
 *              "normalization_context"={
 *                  "groups"={"get-comment-with-author"}
 *              }
 *          }
 *      },
 *      denormalizationContext={
 *          "groups"={"post"}
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment implements AuthorEntityInterface, PublishedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-comment-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"get-comment-with-author", "post"})
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-comment-with-author"})
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-comment-with-author"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BlogPost", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post"})
     */
    private $blogPost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
    {
        $this->published = $published;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(UserInterface $author): AuthorEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    public function getBlogPost(): BlogPost
    {
        return $this->blogPost;
    }

    public function setBlogPost(BlogPost $blogPost): self
    {
        $this->blogPost = $blogPost;

        return $this;
    }
}
