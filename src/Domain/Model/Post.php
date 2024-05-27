<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Entity class for Doctrine ORM.
 */
#[ORM\Entity]
#[ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    #[Groups('post:read')]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[Groups('post:read')]
    protected string $title;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Groups('post:read')]
    protected string $content;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    #[Groups('post:read')]
    protected \DateTimeInterface $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups('user:read')
     */
    private User $user;

    public function __construct(string $title, string $content, \DateTimeInterface $createdAt, User $user)
    {
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
