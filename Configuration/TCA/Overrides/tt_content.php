<?php
if ( ! defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$languageFilePrefix         = 'LLL:EXT:fluid_styled_content/Resources/Private/Language/Database.xlf:';
$frontendLanguageFilePrefix = 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:';

$extConf        = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tkmedia']);
$aspectvalues   = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $extConf['aspectratio']);
$aspectratios   = array();
$aspectratios[] = array('-:-', ' ');
foreach ($aspectvalues as $value) {
    $aspectratios[] = array($value, $value);
}


if ($extConf['enableAr']) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        [
            'tx_tkmedia_aspectratio' => [
                'exclude' => 1,
                'label'   => 'LLL:EXT:tkmedia/Resources/Private/Language/locallang.xml:tkmedia_ratio',
                'config'  => [
                    'type'       => 'select',
                    'renderType' => 'selectSingle',
                    'items'      => $aspectratios,
                    'minitems'   => 1,
                    'maxitems'   => 1,
                ]
            ]
        ]
    );

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('fluid_styled_content')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
            'tt_content',
            'gallerySettings',
            'tx_tkmedia_aspectratio',
            'before:imagecols'
        );
    };

}

if ($extConf['enablePlayer']) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        [
            'tx_tkmedia_playertype' => [
                'label'  => 'LLL:EXT:tkmedia/Resources/Private/Language/locallang.xml:tkmedia_type',
                'config' => [
                    'type'       => 'select',
                    'renderType' => 'selectSingle',
                    'items'      => [
                    ],
                ]
            ],
            'tx_tkmedia_poster'     => [
                'exclude' => 0,
                'label'   => 'LLL:EXT:tkmedia/Resources/Private/Language/locallang.xml:tkmedia_poster',
                'config'  => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                    'tkmedia_poster',
                    [
                        'appearance'    => [
                            'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                        ],
                        'foreign_types' => [
                            '0'                                           => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                        ],
                        'minitems'      => 0,
                        'maxitems'      => 4,
                    ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])
            ]
        ]
    );


    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'tx_tkmedia_poster', 'textmedia', 'after:assets');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'tx_tkmedia_playertype', 'textmedia', 'after:layout');
};
