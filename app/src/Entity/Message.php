<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Contact::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $processed = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->date = new DateTime('now', new DateTimeZone('Europe/Paris'));
    }

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

    public function getAuthor(): ?Contact
    {
        return $this->author;
    }

    public function setAuthor(?Contact $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getProcessed(): ?bool
    {
        return $this->processed;
    }

    public function setProcessed(bool $processed): self
    {
        $this->processed = $processed;

        return $this;
    }

    
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        
        return $this;
    }
    
    public function tojson(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->author->getName(),
            'mail' => $this->author->getMail(),
            'content' => $this->content
        ];
    }
}
