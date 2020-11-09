<?php

namespace App\Entity;

use App\Repository\PrizeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrizeRepository::class)
 */
class Prize
{

    const TYPE_MONEY = "TYPE_MONEY";
    const TYPE_SCORES = "TYPE_SCORES";
    const TYPE_ITEM = "TYPE_ITEM";

    const STATUS_WAIT = "STATUS_WAIT";
    const STATUS_ISSUED = "STATUS_ISSUED";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $money;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scores;

    /**
     * @ORM\OneToOne(targetEntity=Item::class, inversedBy="prize", cascade={"persist", "remove"})
     */
    private $item;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="prizes")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoney(): ?int
    {
        return $this->money;
    }

    public function setMoney(?int $money): self
    {
        $this->money = $money;

        return $this;
    }

    public function getScores(): ?int
    {
        return $this->scores;
    }

    public function setScores(?int $scores): self
    {
        $this->scores = $scores;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public static function getAllTypies()
    {
        return [
            self::TYPE_MONEY,
            self::TYPE_SCORES,
            self::TYPE_ITEM,
        ];
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
