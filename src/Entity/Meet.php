<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeetRepository")
 */
class Meet
{

    const WAIT = 0;
    const FAIL = -1;
    const CONFIRM = 1;

    public function __construct() {
        $this->setStatus(0);
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdMeets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="guestedMeets")
     * @ORM\JoinColumn(nullable=true)
     */
    private $guest;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MeetSlot", inversedBy="meets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $slot;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getGuest(): ?User
    {
        return $this->guest;
    }

    public function setGuest(?User $guest): self
    {
        $this->guest = $guest;

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

    public function getSlot(): ?MeetSlot
    {
        return $this->slot;
    }

    public function setSlot(?MeetSlot $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
