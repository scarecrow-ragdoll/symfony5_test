<?php

namespace App\Command;

use App\Entity\User;
use App\Service\PrizeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendCommand extends Command
{
    protected static $defaultName = 'app:convert';

    /**
     * @var PrizeService
     */
    private $prizeService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(PrizeService $prizeService, EntityManagerInterface $entityManager)
    {
        $this->prizeService = $prizeService;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Send Money to bank')
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('sum', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sum = $input->getArgument('sum');
        $user = $this->entityManager->getRepository(User::class)->findBy(['email' => $input->getArgument('email')]);
        $this->prizeService->sendToBank($sum, $user);
        return Command::SUCCESS;
    }
}