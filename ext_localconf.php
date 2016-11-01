<?php
defined('TYPO3_MODE') or die();


$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tkmedia']);
if ($extConf['enablePlayer']) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
    <INCLUDE_TYPOSCRIPT: source="DIR:EXT:tkmedia/Configuration/PageTs" extensions="ts">
');
}

//Register Renderer
/** @var \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry $rendererRegistry */
$rendererRegistry = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();
$rendererRegistry->registerRendererClass(\ThomasK\Tkmedia\Resource\Rendering\ImageRenderer::class);
$rendererRegistry->registerRendererClass(\ThomasK\Tkmedia\Resource\Rendering\VideoTagRenderer::class);
$rendererRegistry->registerRendererClass(\ThomasK\Tkmedia\Resource\Rendering\YouTubeRenderer::class);
$rendererRegistry->registerRendererClass(\ThomasK\Tkmedia\Resource\Rendering\VimeoRenderer::class);

unset($rendererRegistry);
