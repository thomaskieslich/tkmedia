<?php
namespace ThomasK\Tkmedia\Resource\Rendering;

use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Extbase\Service\TypoScriptService;
use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class ImageRendererConfiguration
 */
class ImageRendererConfiguration
{

    /**
     * @var TypoScriptService
     */
    protected $typoScriptService;

    /**
     * @var array
     */
    protected $settings;

    /**
     * constrct class
     */
    public function __construct()
    {
        $this->settings = [];
        $this->typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $this->tagBuilder = GeneralUtility::makeInstance(TagBuilder::class);

        $configuration = $this->typoScriptService->convertTypoScriptArrayToPlainArray($this->getTypoScriptSetup());

        $settings = ObjectAccess::getPropertyPath(
            $configuration,
            'plugin.tkmedia.settings'
        );
        $settings = is_array($settings) ? $settings : [];

        $this->settings['layoutKey'] =
            (isset($settings['layoutKey']))
                ? $settings['layoutKey']
                : 'default';

        $this->settings['defaultSrc'] =
            (isset($settings['defaultSrc']))
                ? $settings['defaultSrc']
                : null;

        $this->settings['allowUpscaling'] =
            (isset($settings['allowUpscaling']))
                ? $settings['allowUpscaling']
                : null;

        $this->settings['sourceCollection'] =
            (isset($settings['sourceCollection']) && is_array($settings['sourceCollection']))
                ? $settings['sourceCollection']
                : [];
    }

    /**
     * @return string
     */
    public function getAbsRefPrefix()
    {
        $asbRefPrefix = '';
        if ($this->getTypoScriptFrontendController() instanceof TypoScriptFrontendController) {
            $asbRefPrefix = $this->getTypoScriptFrontendController()->absRefPrefix;
        }

        return $asbRefPrefix;
    }

    /**
     * @return string
     */
    public function getLayoutKey()
    {
        return $this->settings['layoutKey'];
    }

    /**
     * @return string
     */
    public function getDefaultSrc()
    {
        return $this->settings['defaultSrc'];
    }

    /**
     * @return array
     */
    public function getSourceCollection()
    {
        return $this->settings['sourceCollection'];
    }

    /**
     * @return int
     */
    public function getAllowUpscaling()
    {
        return (int)$this->settings['allowUpscaling'];
    }

    /**
     * @return array
     */
    protected function getTypoScriptSetup()
    {
        if (!$this->getTypoScriptFrontendController() instanceof TypoScriptFrontendController) {
            return [];
        }

        if (!$this->getTypoScriptFrontendController()->tmpl instanceof TemplateService) {
            return [];
        }

        return $this->getTypoScriptFrontendController()->tmpl->setup;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
