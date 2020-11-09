<?php


namespace App\Twig;


use App\Entity\Item;
use App\Entity\Prize;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('format_prize', [$this, 'formatPrize']),
            new TwigFunction('get_all_prize', [$this, 'getAllPrize']),
        ];
    }

    public function formatPrize(Prize $prize): string
    {
        if ($prize->getType() == Prize::TYPE_MONEY) {
            return "Денежный приз в размере " . $prize->getMoney();
        } elseif ($prize->getType() == Prize::TYPE_ITEM) {
            return $prize->getItem()->getName();
        } else {
            return $prize->getScores() . " бонусных балов";
        }
    }

    public function getAllPrize(User $user): string
    {
        $money = $this->entityManager->getRepository(Prize::class)->getUserMoneySum($user);
        $scores = $this->entityManager->getRepository(Prize::class)->getUserScoresSum($user);
        $items = $this->entityManager->getRepository(Item::class)->getUserItems($user);
        $itemsString = "";
        foreach ($items as $item) {
            $itemsString .= $item->getName() . ", ";
        }
        return "Деньги - " . $money . PHP_EOL . "Баллы - " . $scores . PHP_EOL . " Предметы - " . $itemsString;
    }
}