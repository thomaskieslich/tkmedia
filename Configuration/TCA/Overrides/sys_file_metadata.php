<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// We only want to have file relations if extension File advanced metadata is loaded.
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('filemetadata')) {
    $configuration = '--div--;Cover, media_cover';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file_metadata', $configuration);
}

$tca = [
    'columns' => [
        'media_cover' => [
            'label' => 'Cover',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'media_cover',
                [
                    'minitems' => 0,
                    'maxitems' => 1,
                ],
                'jpg,jpeg'
            ),
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA']['sys_file_metadata'], $tca);
