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
    public function index(GameRepository $gameRepository) {
        return $this->render('homepage/index.html.twig', array(
            'games' => [
                'recentlyPlayed' => $gameRepository->getRecentlyPlayedGames(),
                'mostPlayed'  =>  $gameRepository->getMostPlayedGames(9)
            ]
        ));
    }
}
