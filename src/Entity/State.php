<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StateRepository::class)
 */
class State
{
    /** const Statut commande **/
    public const STATE_WAIT = 'WAIT';
    public const STATE_PAID = 'PAID';
    public const STATE_IN_PROGRESS = 'IN_PROGRESS';
    public const STATE_DELEVERY = 'DELEVERY';
    public const STATE_FINISH = 'FINISH';
    public const STATE_INCOMPLETE = 'INCOMPLETE';

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="state")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setState($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getState() === $this) {
                $order->setState(null);
            }
        }

        return $this;
    }
}
