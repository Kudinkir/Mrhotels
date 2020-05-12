<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $entry_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $exit_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $guest_email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $guest_phone;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $guests_quantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Rooms", inversedBy="reservations")
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quotes", mappedBy="reservation", orphanRemoval=true)
     */
    private $qoutes;

    public function __construct()
    {
        $this->qoutes = new ArrayCollection();
    }

    public function __toString()
    {
        return ''.$this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entry_date;
    }

    public function setEntryDate(?\DateTimeInterface $entry_date): self
    {
        $this->entry_date = $entry_date;

        return $this;
    }

    public function getExitDate(): ?\DateTimeInterface
    {
        return $this->exit_date;
    }

    public function setExitDate(?\DateTimeInterface $exit_date): self
    {
        $this->exit_date = $exit_date;

        return $this;
    }

    public function getGuestEmail(): ?string
    {
        return $this->guest_email;
    }

    public function setGuestEmail(?string $guest_email): self
    {
        $this->guest_email = $guest_email;

        return $this;
    }

    public function getGuestPhone(): ?string
    {
        return $this->guest_phone;
    }

    public function setGuestPhone(?string $guest_phone): self
    {
        $this->guest_phone = $guest_phone;

        return $this;
    }

    public function getGuestsQuantity(): ?int
    {
        return $this->guests_quantity;
    }

    public function setGuestsQuantity(?int $guests_quantity): self
    {
        $this->guests_quantity = $guests_quantity;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRoom(): ?Rooms
    {
        return $this->room;
    }

    public function setRoom(?Rooms $room): self
    {
        $this->room = $room;

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
            $qoute->setReservation($this);
        }

        return $this;
    }

    public function removeQoute(Quotes $qoute): self
    {
        if ($this->qoutes->contains($qoute)) {
            $this->qoutes->removeElement($qoute);
            // set the owning side to null (unless already changed)
            if ($qoute->getReservation() === $this) {
                $qoute->setReservation(null);
            }
        }

        return $this;
    }
}
