<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AppController
 */
class AppController extends Controller
{

    /**
     * @param GameRepository $gameRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(GameRepository $gameRepository)
    {

        return $this->render('homepage/homepage.html.twig', array(
            'recentlyGames' => $gameRepository->getRecentlyPlayedGames()
        ));
    }
}
