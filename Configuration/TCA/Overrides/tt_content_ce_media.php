<?php
defined('TYPO3_MODE') || die();

$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tkmedia']);
$aspectvalues = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $extConf['aspectratio']);
$aspectratios = array();
$aspectratios[] = array('-:-', 0);
foreach ($aspectvalues as $value) {
    $divider = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(':', $value);
    $ratio = 0;
    if (count($divider) == 2) {
        $ratio = round($divider[0] / $divider[1], 8);
    }
    $aspectratios[] = [(string)$value, (string)$ratio];
}

/***************
 * Register column
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    [
        'imageratio' => [
            'label' => 'LLL:EXT:tkmedia/Resources/Private/Language/locallang.xlf:label.imageratio',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => $aspectratios,
            ]
        ]
    ]
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'gallerySettings',
    'imageratio',
    'before:imagecols'
);

/***************
 * change media preview size
 */
$GLOBALS['TCA']['tt_content']['types']['textmedia']['columnsOverrides']['assets']['config']['appearance'] = [
    'headerThumbnail' => [
        'width' => '100',
        'height' => '100c'
    ]
];