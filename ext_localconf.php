<?php
defined('TYPO3_MODE') or die();


//Register Renderer
/** @var \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry $rendererRegistry */
$rendererRegistry = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();
$rendererRegistry->registerRendererClass(\ThomasK\Tkmedia\Resource\Rendering\YouTubeRenderer::class);
$rendererRegistry->registerRendererClass(\ThomasK\Tkmedia\Resource\Rendering\VimeoRenderer::class);
$rendererRegistry->registerRendererClass(\ThomasK\Tkmedia\Resource\Rendering\VideoTagRenderer::class);

unset($rendererRegistry);
