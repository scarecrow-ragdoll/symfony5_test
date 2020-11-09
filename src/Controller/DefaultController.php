<?php

namespace App\Controller;

use App\Entity\Prize;
use App\Service\PrizeService;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
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

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/get_prize", name="get_prize")
     */
    public function getPrize(): Response
    {
        $prize = $this->prizeService->getRandomPrize();
        return new RedirectResponse($this->generateUrl('prize', ['prize' => $prize->getId()]));
    }

    /**
     * @Route("/prize-{prize}", name="prize")
     * @param Prize $prize
     */
    public function prize(Prize $prize): Response
    {
        if ($prize->getUser() != $this->getUser() or $prize->getStatus() != Prize::STATUS_WAIT)
            throw $this->createNotFoundException('Приз не существует');
        return $this->render('default/prize.html.twig', [
            'prize' => $prize,
        ]);
    }

    /**
     * @Route("/accept-prize-{prize}", name="accept_prize")
     * @param Prize $prize
     */
    public function acceptPrize(Prize $prize): Response
    {
        if ($prize->getUser() != $this->getUser() or $prize->getStatus() != Prize::STATUS_WAIT)
            throw $this->createNotFoundException('Приз не существует');
        $prize->setStatus(Prize::STATUS_ISSUED);
        $this->entityManager->persist($prize);
        $this->entityManager->flush();
        return new RedirectResponse($this->generateUrl('index'));
    }

    /**
     * @Route("/convert", name="convert")
     * @param Request $request
     */
    public function convert(Request $request): Response
    {
        $sum = $request->getQuery()->get('sum', 0);
        $this->prizeService->convertMoneyToScores($sum, $this->getUser());
        return new RedirectResponse($this->generateUrl('index'));
    }
}
