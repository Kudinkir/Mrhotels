<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomsRepository")
 */
class Rooms
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $square;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $smoking;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hotels", inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hotel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="room")
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quotes", mappedBy="room", orphanRemoval=true)
     */
    private $qoutes;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->qoutes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getHotel() .' room_id is '. $this->getId(). ' '. $this->getCategory();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSquare(): ?float
    {
        return $this->square;
    }

    public function setSquare(?float $square): self
    {
        $this->square = $square;

        return $this;
    }

    public function getSmoking(): ?bool
    {
        return $this->smoking;
    }

    public function setSmoking(?bool $smoking): self
    {
        $this->smoking = $smoking;

        return $this;
    }

    public function getHotel(): ?Hotels
    {
        return $this->hotel;
    }

    public function setHotel(?Hotels $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setRoom($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getRoom() === $this) {
                $reservation->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Quotes[]
     */
    public function getQoutes(): Collection
    {
        return $this->qoutes;
    }

    public function addQoute(Quotes $qoute): self
    {
        if (!$this->qoutes->contains($qoute)) {
            $this->qoutes[] = $qoute;
            $qoute->setRoom($this);
        }

        return $this;
    }

    public function removeQoute(Quotes $qoute): self
    {
        if ($this->qoutes->contains($qoute)) {
            $this->qoutes->removeElement($qoute);
            // set the owning side to null (unless already changed)
            if ($qoute->getRoom() === $this) {
                $qoute->setRoom(null);
            }
        }

        return $this;
    }
}
