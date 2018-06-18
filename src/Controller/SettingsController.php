<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Form\Type\SettingsType;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SettingsController
 */
class SettingsController extends Controller
{
    /**
     * @param Request $request
     * @param SettingsRepository $settingsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function edit(Request $request, SettingsRepository $settingsRepository)
    {
        $settings = [
            'steamApiKey' => $bandName = $settingsRepository->findOneByKeyOrCreate(Settings::STEAM_API_KEY),
            'steamUserId' => $bandName = $settingsRepository->findOneByKeyOrCreate(Settings::STEAM_USER_ID),
            'steamUserName' => $bandName = $settingsRepository->findOneByKeyOrCreate(Settings::STEAM_USER_NAME),
            'gaTracking' => $bandName = $settingsRepository->findOneByKeyOrCreate(Settings::GA_TRACKING),
            'defaultCurrency' => $bandName = $settingsRepository->findOneByKeyOrCreate(Settings::DEFAULT_CURRENCY),
        ];

        $form = $this->createForm(
            SettingsType::class,
            $settings,
            [
                'action' => $this->generateUrl('settings_edit'),
                'method' => 'POST',
            ]
        );

        if (in_array($request->getMethod(), ['POST'])) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                foreach ($form->getData() as $key => $value) {
                    $setting = $settings[$key];
                    $setting->setSettingsValue($value);
                    $settingsRepository->save($setting);
                }
            }
        }
        return $this->render(
            'Settings/edit.html.twig',
            [
                'settings' => $bandName,
                'form' => $form->createView(),
            ]
        );
    }
}
