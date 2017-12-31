<?php

namespace App\Service;

use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

/**
 * Class TranslationService
 */
class TranslationService
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * TranslationService constructor.
     * @param string $locale
     * @param string $projectDir
     */
    public function __construct(string $locale, string $projectDir)
    {
        $translationDirectory = $projectDir . '/translations/'. $locale;
        $this->translator = new Translator($locale);
        $this->translator->addLoader('yaml', new YamlFileLoader());
        $this->translator->addResource('yaml', $translationDirectory . '/game.yml', $locale);
        $this->translator->addResource('yaml', $translationDirectory . '/time.yml', $locale);
    }

    /**
     * @param $id
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param $id
     * @param $number
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     * @return string
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null): string
    {
        return $this->translator->transChoice($id, $number, $parameters, $domain, $locale);
    }
}
