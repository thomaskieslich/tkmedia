<?php
defined('TYPO3_MODE') or die();


$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tkmedia']);
$aspectvalues = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $extConf['aspectratio']);
$aspectratios = [];
foreach ($aspectvalues as $value) {
    $divider = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(':', $value);
    $ratio = 0;
    if (count($divider) == 2) {
        $ratio = $divider[0] / $divider[1];
    }
    $aspectratios[$value] = [
        'title' => $value,
        'value' => $ratio
    ];
}

$aspectratios['NaN'] = [
    'title' => 'LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
    'value' => 0.0
];

$additionalCropVariants = [
    'default' => [
        'title' => 'LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf:imwizard.crop_variant.default',
        'allowedAspectRatios' => $aspectratios,
        'selectedRatio' => 'NaN',
    ],
];

if (!isset($GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants'])) {
    $GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants'] = [];
}

$GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants'] = array_replace_recursive(
    $GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['cropVariants'],
    $additionalCropVariants
);
