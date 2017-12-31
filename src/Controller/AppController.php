<?php

namespace App\Controller;

use App\Service\TranslationService;
use App\Service\Twig\HomepageTransformatorService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AppController
 */
class AppController extends Controller
{

    /**
     * @param HomepageTransformatorService $homepageTransformatorService
     * @param TranslationService $translator
     * @return Response
     */
    public function indexAction(
        HomepageTransformatorService $homepageTransformatorService,
        TranslationService $translator
    ) {
        return $this->render('homepage/index.html.twig', array(
            'games' => [
                'recentlyPlayed' => [
                    'title' => $translator->trans('game.recently_played.title'),
                    'content' => $homepageTransformatorService->transformRecentlyPlayedGames(),
                ],
                'mostPlayed'  => [
                    'title' => $translator->trans('game.most_played.title'),
                    'content' => $homepageTransformatorService->transformTopPlayedGames()
                ]
            ]
        ));
    }
}
