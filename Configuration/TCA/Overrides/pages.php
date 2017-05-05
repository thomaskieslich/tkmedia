<?php
defined('TYPO3_MODE') or die();

// Add pageTSconfig
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'tkmedia',
    'Configuration/TSconfig/Page/Page.tsconfig',
    'Tkmedia'
);
