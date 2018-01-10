<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AppController
 */
class AppController extends Controller
{

    /**
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function recentlyPlayed(GameRepository $gameRepository)
    {
        return $this->render('homepage/recentlyPlayed.html.twig', array(
            'games' => $gameRepository->getRecentlyPlayedGames()
        ));
    }

    /**
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function mostPlayed(GameRepository $gameRepository)
    {
        return $this->render('homepage/mostPlayed.html.twig', array(
            'games' => $gameRepository->getMostPlayedGames(9)
        ));
    }
}
