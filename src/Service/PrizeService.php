<?php


namespace App\Service;


use App\Entity\Item;
use App\Entity\Prize;
use App\Entity\User;
use App\Repository\ItemRepository;
use App\Repository\PrizeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class PrizeService
{

    const ALL_MONEY = "500";

    const MONEY_MIN = 10;
    const MONEY_MAX = 100;

    const SCORES_MIN = 10;
    const SCORES_MAX = 100;

    const SCORES_COFFICIENT = 1;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getRandomPrize(): Prize
    {
        $typies = Prize::getAllTypies();
        $type = $typies[array_rand($typies)];
        while (!$this->checkPrize($type)) {
            unset($typies[array_search($type, $typies)]);
            $type = $typies[array_rand($typies)];
        }
        $prize = $this->generatePrizeByType($type);
        $this->entityManager->persist($prize);
        $this->entityManager->flush();
        return $prize;
    }

    private function generatePrizeByType(string $type): Prize
    {
        $prize = new Prize();
        $prize->setType($type);
        $prize->setStatus(Prize::STATUS_WAIT);
        $prize->setUser($this->security->getUser());
        if ($type === Prize::TYPE_MONEY) {
            $prize->setMoney($this->getMoney());
        }
        if ($type === Prize::TYPE_ITEM) {
            $prize->setItem($this->getFreeItem());
        }
        if ($type === Prize::TYPE_SCORES) {
            $prize->setScores($this->getScores());
        }
        return $prize;
    }

    private function checkPrize(string $type): bool
    {
        if ($type === Prize::TYPE_MONEY) {
            return $this->haveFreeMoney();
        }
        if ($type === Prize::TYPE_ITEM) {
            return $this->haveFreeItem();
        }
        return true;
    }

    private function haveFreeMoney(): bool
    {
        $moneySum = $this->entityManager->getRepository(Prize::class)->getMoneySum();
        if ($moneySum < self::ALL_MONEY) {
            return true;
        }
        return false;
    }

    private function getMoney(): int
    {
        $moneySum = $this->entityManager->getRepository(Prize::class)->getMoneySum();
        $maxMoney = self::ALL_MONEY - $moneySum;
        $money = rand(self::MONEY_MIN, self::MONEY_MAX);
        if ($money > $maxMoney) {
            return $maxMoney;
        }
        return $money;
    }

    private function haveFreeItem(): bool
    {
        return count($this->entityManager->getRepository(Item::class)->getFreeItems()) > 0;
    }

    private function getFreeItem(): Item
    {
        $freeItems = $this->entityManager->getRepository(Item::class)->getFreeItems();
        return $freeItems[array_rand($freeItems)];
    }

    private function getScores(): int
    {
        return rand(self::SCORES_MIN, self::SCORES_MAX);
    }

    public function sendToBank(int $sum, User $user)
    {
        $userMoney = $this->entityManager->getRepository(Prize::class)->getUserMoneySum($user);
        if ($userMoney < $sum)
            $sum = $userMoney;
        if ($sum > 0) {

            //send to bank api request

            $prize = new Prize();
            $prize->setStatus(Prize::STATUS_ISSUED);
            $prize->setType(Prize::TYPE_MONEY);
            $prize->setMoney(0 - $sum);
            $this->entityManager->persist($prize);
            $this->entityManager->flush();
        }
    }

    public function convertMoneyToScores(int $scoresSum, User $user)
    {
        $userScores = $this->entityManager->getRepository(Prize::class)->getUserScoresSum($user);
        if ($userScores < $scoresSum)
            $scoresSum = $userScores;
        if ($scoresSum > 0) {
            $prizeMoney = new Prize();
            $prizeMoney->setStatus(Prize::STATUS_ISSUED);
            $prizeMoney->setType(Prize::TYPE_MONEY);
            $prizeMoney->setMoney(0 - ($scoresSum * self::SCORES_COFFICIENT));
            $this->entityManager->persist($prizeMoney);

            $prizeScore = new Prize();
            $prizeScore->setStatus(Prize::STATUS_ISSUED);
            $prizeScore->setType(Prize::TYPE_SCORES);
            $prizeScore->setPoint($scoresSum * self::SCORES_COFFICIENT);
            $this->entityManager->persist($prizeScore);

            $this->entityManager->flush();
        }
    }

}