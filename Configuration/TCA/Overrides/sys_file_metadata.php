<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

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
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                title,crop,
                                --palette--;;filePalette'
                            ],
                        ],
                    ],
                ],
                'jpg,jpeg,png'
            ),
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA']['sys_file_metadata'], $tca);
