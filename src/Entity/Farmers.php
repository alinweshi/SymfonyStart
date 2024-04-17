<?php

namespace App\Entity;

use App\Repository\FarmersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[ORM\Entity(repositoryClass: FarmersRepository::class)]
class Farmers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    #[NotBlank]
    #[Assert\Length(min: 5, max: 255,
        minMessage: 'The first name must be at least 5 characters long',
        maxMessage: 'The first name must be at most 255 characters long',
    )]
    #[ORM\Column(type: 'string')]

    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255,
        minMessage: 'The first name must be at least 5 characters long',
        maxMessage: 'The first name must be at most 255 characters long',
    )]
    #[ORM\Column(type: 'string')]

    private ?string $lastName = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: 'string')]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    protected string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255,
        minMessage: 'The phone number must be at least 10 characters long',
        maxMessage: 'The phone number must be at most 11 characters long',
    )]
    #[ORM\Column(type: 'string')]

    private ?string $phone = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(type: 'string')]
    #[Assert\PasswordStrength([
            'minScore' => PasswordStrength::STRENGTH_VERY_STRONG, // Very strong password required
            'message' => 'Your password is too easy to guess. Company\'s security policy requires to use a stronger password.',
        ])]
    private ?string $password = null;

    #[Assert\NotBlank]
    #[Assert\Image(
        mimeTypes: [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/webp',
            'image/avif',
            'image/svg+xml',
        ],
        mimeTypesMessage: "The image is not a valid image. Please use a valid image.",
    )]
    #[ORM\Column(type: 'string')]

    private ?File $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
    public function setImage(?File $image = null): void
    {
        $this->image = $image;
    }

    public function getImage(): File
    {
        return $this->image;
    }
}
