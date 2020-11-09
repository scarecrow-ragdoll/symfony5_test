<?php


namespace App\Tests;


use App\Repository\PrizeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CovertTest extends WebTestCase
{

    //configure revert database after test
    public function testConvert()
    {
        $scores = 2;
        $client = self::createClient();
        $container = $client->getContainer();

        $testUser = $container->get(UserRepository::class)->findOneByEmail('test@test.ru');
        $client->loginUser($testUser);

        $originalScores = $container->get(PrizeRepository::class)->getUserScoresSum($testUser);

        $client->request('GET', '/convert?sum=' . $scores);

        $resultScores = $container->get(PrizeRepository::class)->getUserScoresSum($testUser);

        $result = $originalScores - $scores;
        if ($result < 0) {
            $result = 0;
        }

        $this->assertEquals($result, $resultScores);
    }
}