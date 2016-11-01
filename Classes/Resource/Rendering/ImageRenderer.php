<?php
namespace ThomasK\Tkmedia\Resource\Rendering;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Class ImageRenderer
 */
class ImageRenderer implements FileRendererInterface
{

    /**
     * @var TagBuilder
     */
    static protected $tagBuilder;

    /**
     * @var ImageRendererConfiguration
     */
    static protected $configuration;

    /**
     * @var array
     */
    protected $possibleMimeTypes = [
        'image/jpg',
        'image/jpeg',
        'image/png',
        'image/gif',
    ];

    /**
     * @var array
     */
    protected $sizes = [];

    /**
     * @var array
     */
    protected $media = [];

    /**
     * @var array
     */
    protected $srcset = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $defaultWidth;

    /**
     * @var string
     */
    protected $defaultHeight;

    /**
     * @var string
     */
    protected $layoutKey;

    /**
     * @var array
     */
    protected $aspectratio;


    /**
     * @return ImageRendererConfiguration
     */
    protected function getConfiguration()
    {
        if ( ! static::$configuration instanceof ImageRendererConfiguration) {
            static::$configuration = GeneralUtility::makeInstance(ImageRendererConfiguration::class);
        }

        return static::$configuration;
    }

    /**
     * @return TagBuilder
     */
    protected function getTagBuilder()
    {
        if ( ! static::$tagBuilder instanceof TagBuilder) {
            static::$tagBuilder = GeneralUtility::makeInstance(TagBuilder::class);
        }

        return static::$tagBuilder;
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\FileReference $file
     *
     * @return array|bool
     */
    protected function getParentData($file)
    {
        /** @var \TYPO3\CMS\Core\Database\DatabaseConnection $database */
        $database = $GLOBALS['TYPO3_DB'];

        $record = $database->exec_SELECTgetSingleRow(
            '*',
            $file->getProperty('tablenames'),
            'uid = ' . $file->getProperty('uid_foreign')
        );

        $aspectratio = GeneralUtility::trimExplode(':', $record['tx_tkmedia_aspectratio']);

        if (count($aspectratio) === 2) {
            $this->aspectratio = $aspectratio;
        }

        return false;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 5;
    }

    /**
     * @param FileInterface $file
     *
     * @return bool
     */
    public function canRender(FileInterface $file)
    {
        return TYPO3_MODE === 'FE' && in_array($file->getMimeType(), $this->possibleMimeTypes, true);
    }

    /**
     * @param FileInterface $file
     * @param int|string $width TYPO3 known format; examples: 220, 200m or 200c
     * @param int|string $height TYPO3 known format; examples: 220, 200m or 200c
     * @param array $options
     * @param bool $usedPathsRelativeToCurrentScript See $file->getPublicUrl()
     *
     * @return string
     */
    public function render(
        FileInterface $file,
        $width,
        $height,
        array $options = [],
        $usedPathsRelativeToCurrentScript = false
    ) {
        $this->reset();
        $configuration = $this->getConfiguration();
        if ($configuration->getLayoutKey() != '') {
            $this->layoutKey = $configuration->getLayoutKey();
        } else {
            $this->layoutKey = 'default';
        }


        $this->defaultWidth  = $width;
        $this->defaultHeight = $height;

        if (is_callable([$file, 'getOriginalFile'])) {
            /** @var FileReference $file */
            $originalFile = $file->getOriginalFile();
        } else {
            $originalFile = $file;
        }

        try {
            $defaultProcessConfiguration           = [];
            $defaultProcessConfiguration['width']  = $this->defaultWidth;
            $defaultProcessConfiguration['height'] = $this->defaultHeight;
            $defaultProcessConfiguration['crop']   = $file->getProperty('crop');
        } catch (\InvalidArgumentException $e) {
            $defaultProcessConfiguration['crop'] = '';
        }

        $this->getParentData($file);

        if ($this->aspectratio) {
            $defaultProcessConfiguration = $this->calcCrop($defaultProcessConfiguration);
            $this->defaultWidth          = $defaultProcessConfiguration['width'];
            $this->defaultHeight         = $defaultProcessConfiguration['height'];
        }

        if ($this->layoutKey != 'default') {
            $this->processSourceCollection($originalFile, $defaultProcessConfiguration);
        }

        $defaultSrc = $configuration->getDefaultSrc();

        if ( ! $defaultSrc || empty($this->data)) {
            $src = $originalFile->process(
                ProcessedFile::CONTEXT_IMAGECROPSCALEMASK,
                $defaultProcessConfiguration
            )->getPublicUrl();
        } else {
            $src = $defaultSrc;
        }

        try {
            $alt = $options['alt'];
        } catch (\InvalidArgumentException $e) {
            $alt = '';
        }

        try {
            $title = $options['title'];
        } catch (\InvalidArgumentException $e) {
            $title = '';
        }

        $imageTag = $this->buildImageTag($src, $alt, $title);

        if ($this->layoutKey === 'data') {
            $imageTag .= '<noscript><img src="' . $this->data['data-' . (int)$this->defaultWidth] . '" width="' . (int)$this->defaultWidth . '" alt="noscript"/></noscript>';
        }

        return $imageTag;
    }

    /**
     * @return void
     */
    protected function reset()
    {
        $this->sizes       = [];
        $this->srcset      = [];
        $this->data        = [];
        $this->aspectratio = [];
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\File $originalFile
     * @param array $defaultProcessConfiguration
     */
    protected function processSourceCollection(File $originalFile, array $defaultProcessConfiguration)
    {
        $configuration = $this->getConfiguration();

        $renderItems = [];

        foreach ($configuration->getSourceCollection() as $sourceCollection) {
            try {
                if ( ! is_array($sourceCollection)) {
                    throw new \RuntimeException();
                }
                $item = [];
                if (isset($sourceCollection['width']) && (int)$sourceCollection['width'] > 0) {
                    $item['width'] = (int)$sourceCollection['width'];
                } else {
                    $item['width'] = $this->defaultWidth;
                }

                if (isset($sourceCollection['pixelDensity']) && (int)$sourceCollection['pixelDensity'] > 0) {
                    $item['width'] = (int)$item['width'] * $sourceCollection['pixelDensity'];
                }

                if (isset($sourceCollection['quality']) && (int)$sourceCollection['quality'] > 0) {
                    $item['quality'] = (int)$sourceCollection['quality'];
                }

                if (isset($sourceCollection['srcset'])) {
                    $item['srcset'] = $sourceCollection['srcset'];
                } elseif ( ! $sourceCollection['srcset']) {
                    $item['srcset'] = (int)$item['width'] . 'w';
                }

                if (isset($sourceCollection['sizes']) && $sourceCollection['sizes'] != '') {
                    $item['sizes'] = $sourceCollection['sizes'];
                }

                if (isset($sourceCollection['media']) && $sourceCollection['media'] != '') {
                    $item['media'] = $sourceCollection['media'];
                }

                if (isset($sourceCollection['dataKey'])) {
                    $item['dataKey'] = $sourceCollection['dataKey'];
                } elseif ( ! $sourceCollection['dataKey']) {
                    $item['dataKey'] = (int)$item['width'];
                }

                $renderItems[$item['width']] = $item;

            } catch
            (\Exception $ignoredException) {
                continue;
            }
        }


        foreach ($renderItems as $image) {
            $localProcessingConfiguration          = $defaultProcessConfiguration;
            $localProcessingConfiguration['width'] = $image['width'];

            $originalWidth  = $originalFile->getProperty('width');
            $originalHeight = $originalFile->getProperty('height');


            if (isset($image['quality'])) {
                $localProcessingConfiguration['additionalParameters'] = '-quality ' . $image['quality'];
            }

            $ar = (int)$defaultProcessConfiguration['width'] / (int)$defaultProcessConfiguration['height'];

            if ($originalFile->getProperty('width') < (int)$image['width']) {
                $image['width'] = $originalWidth;
            }

            $localProcessingConfiguration['height'] = ((int)$image['width']) / $ar . 'c';

            if (
                $configuration->getAllowUpscaling() != 1
            ) {
                if (
                    $originalWidth < (int)$localProcessingConfiguration['width']
                    || $originalHeight < (int)$localProcessingConfiguration['height']
                ) {
                    continue;
                }
            }

            $processedFile = $originalFile->process(
                ProcessedFile::CONTEXT_IMAGECROPSCALEMASK,
                $localProcessingConfiguration
            );

            $url = $configuration->getAbsRefPrefix() . $processedFile->getPublicUrl();

            if ($image['srcset']) {
                $this->srcset[] = $url . ' ' . $image['srcset'];
            }

            if ($image['sizes']) {
                $this->sizes[] = $image['sizes'];
            }

            if ($image['media']) {
                $this->media[] = $image['media'];
            }

            if ($image['dataKey']) {
                $this->data['data-' . $image['dataKey']] = $url;
            }

        }

    }

    /**
     * @param string $src
     * @param string $alt
     * @param string $title
     *
     * @return string
     */
    protected function buildImageTag($src, $alt = '', $title = '')
    {
        $tagBuilder    = $this->getTagBuilder();
        $configuration = $this->getConfiguration();

        $tagBuilder->reset();
        $tagBuilder->setTagName('img');
        $tagBuilder->addAttribute('src', $src);
        $tagBuilder->addAttribute('alt', $alt);
        $tagBuilder->addAttribute('title', $title);

        switch ($configuration->getLayoutKey()) {
            case 'srcset':
                if ( ! empty($this->srcset)) {
                    $tagBuilder->addAttribute('srcset', implode(', ', $this->srcset));
                }
                if ( ! empty($this->sizes)) {
                    $tagBuilder->addAttribute('sizes', implode(', ', $this->sizes));
                } elseif (empty($this->media)) {
                    $tagBuilder->addAttribute('sizes', (int)$this->defaultWidth . 'px');
                } else {
                    $tagBuilder->addAttribute('sizes', implode(', ', $this->media) . ', ' . (int)$this->defaultWidth . 'px');
                }

                $tagBuilder->addAttributes([
                    'style' => 'width: ' . (int)$this->defaultWidth . 'px;'
                ]);

                break;
            case 'data':
                $tagBuilder->addAttributes([
                    'class' => 'lazyload',
                    'width' => (int)$this->defaultWidth
                ]);
                $tagBuilder->addAttribute('data-source-width', (int)$this->defaultWidth);
                if ( ! empty($this->data)) {
                    foreach ($this->data as $key => $value) {
                        $tagBuilder->addAttribute($key, $value);
                    }
                }
                break;
            default:
                $tagBuilder->addAttributes([
                    'width'  => (int)$this->defaultWidth,
                    'height' => (int)$this->defaultHeight,
                ]);
                break;
        }

        return $tagBuilder->render();
    }

    /**
     * calculate new processing values
     *
     * @param array $processingConfiguration
     *
     * @return array mixed
     */
    protected function calcCrop($processingConfiguration)
    {
        $width = (int)$processingConfiguration['width'];
        $ratio = $this->aspectratio[1] / $this->aspectratio[0];

        $processingConfiguration['width']  = (int)$width . 'c';
        $processingConfiguration['height'] = (int)$width * $ratio . 'c';

        return $processingConfiguration;
    }
}
