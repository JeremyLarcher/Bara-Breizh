<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min: 5, max: 400)]
    private ?string $texte = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max:5)]
    #[Assert\NotBlank(message: 'Veuillez sélectionner une note')]
    private ?int $note = null;

    #[ORM\OneToOne(inversedBy: 'commentaire', cascade: ['persist', 'remove'])]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        // unset the owning side of the relation if necessary
        if ($utilisateur === null && $this->utilisateur !== null) {
            $this->utilisateur->setCommentaire(null);
        }

        // set the owning side of the relation if necessary
        if ($utilisateur !== null && $utilisateur->getCommentaire() !== $this) {
            $utilisateur->setCommentaire($this);
        }

        $this->utilisateur = $utilisateur;

        return $this;
    }
}
