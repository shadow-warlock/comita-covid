<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeetSlotRepository")
 */
class MeetSlot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Accessor(getter="getTime")
     */
    private $time;

    /**
     * @Serializer\MaxDepth(2)
     * @ORM\OneToMany(targetEntity="App\Entity\Meet", mappedBy="slot")
     */
    private $meets;

    /**
     * @Serializer\VirtualProperty()
     */
    public function isActual()
    {
        $d1 = (new \DateTime(explode(" - ", $this->time)[0]))->setDate(2020, 4, 3);
        return $d1->getTimestamp() > (new \DateTime("now"))->getTimestamp();
    }


    public function __construct()
    {
        $this->meets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return Collection|Meet[]
     */
    public function getMeets(): Collection
    {
        return $this->meets;
    }

    public function addMeet(Meet $meet): self
    {
        if (!$this->meets->contains($meet)) {
            $this->meets[] = $meet;
            $meet->setSlot($this);
        }

        return $this;
    }

    public function removeMeet(Meet $meet): self
    {
        if ($this->meets->contains($meet)) {
            $this->meets->removeElement($meet);
            // set the owning side to null (unless already changed)
            if ($meet->getSlot() === $this) {
                $meet->setSlot(null);
            }
        }

        return $this;
    }
}
