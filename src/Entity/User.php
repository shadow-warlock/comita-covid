<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    const ADMIN = "ROLE_ADMIN";
    const USER = "ROLE_USER";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $login;

    /**
     * @ORM\Column(type="string")
     */
    private $role = "";

    /**
     * @Serializer\Exclude()
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $access;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meet", mappedBy="creator", orphanRemoval=true)
     */
    private $createdMeets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meet", mappedBy="guest", orphanRemoval=true)
     */
    private $guestedMeets;

    public function __construct()
    {
        $this->createdMeets = new ArrayCollection();
        $this->guestedMeets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = [$this->role];
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public static function generatePassword(){
        $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $max=10;
        $size=StrLen($chars)-1;
        $password=null;
        while($max--)
            $password.=$chars[rand(0,$size)];
        return $password;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAccess(): ?bool
    {
        return $this->access;
    }

    public function setAccess(bool $access): self
    {
        $this->access = $access;

        return $this;
    }

    /**
     * @return Collection|Meet[]
     */
    public function getCreatedMeets(): Collection
    {
        return $this->createdMeets;
    }

    public function addCreatedMeet(Meet $createdMeet): self
    {
        if (!$this->createdMeets->contains($createdMeet)) {
            $this->createdMeets[] = $createdMeet;
            $createdMeet->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedMeet(Meet $createdMeet): self
    {
        if ($this->createdMeets->contains($createdMeet)) {
            $this->createdMeets->removeElement($createdMeet);
            // set the owning side to null (unless already changed)
            if ($createdMeet->getCreator() === $this) {
                $createdMeet->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Meet[]
     */
    public function getGuestedMeets(): Collection
    {
        return $this->guestedMeets;
    }

    public function addGuestedMeet(Meet $guestedMeet): self
    {
        if (!$this->guestedMeets->contains($guestedMeet)) {
            $this->guestedMeets[] = $guestedMeet;
            $guestedMeet->setGuest($this);
        }

        return $this;
    }

    public function removeGuestedMeet(Meet $guestedMeet): self
    {
        if ($this->guestedMeets->contains($guestedMeet)) {
            $this->guestedMeets->removeElement($guestedMeet);
            // set the owning side to null (unless already changed)
            if ($guestedMeet->getGuest() === $this) {
                $guestedMeet->setGuest(null);
            }
        }

        return $this;
    }
}
