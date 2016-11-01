<?php
defined('TYPO3_MODE') or die();


$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tkmedia']);
$aspectvalues = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $extConf['aspectratio']);
$aspectratios = array();
foreach ($aspectvalues as $value) {
    $divider = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(':', $value);
    $ratio = 0;
    if (count($divider) == 2) {
        $ratio = $divider[0] / $divider[1];
    }
    $aspectratios["$ratio"] = $value;
}
$aspectratios['NaN'] = 'LLL:EXT:lang/locallang_wizards.xlf:imwizard.ratio.free';

$tca = array(
    'columns' => array(
        'crop' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.crop',
            'config' => array(
                'type' => 'imageManipulation',
                'ratios' => $aspectratios,
                'enableZoom' => false,
//                'allowedExtensions' => null
                'allowedExtensions' => 'gif,jpg,jpeg,tif,png'
            )
        )
    ),
    'types' => array(),
);

if ($extConf['enableAr']) {
    $GLOBALS['TCA']['sys_file_reference'] = array_replace_recursive(
        $GLOBALS['TCA']['sys_file_reference'],
        $tca
    );
};
