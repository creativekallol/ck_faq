<?php

declare(strict_types=1);

use Creativekallol\CkFaq\Controller\FaqController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addTypoScriptConstants(
    "@import 'EXT:ck_faq/Configuration/TypoScript/constants.typoscript'"
);

ExtensionManagementUtility::addTypoScriptSetup(
    "@import 'EXT:ck_faq/Configuration/TypoScript/setup.typoscript'"
);

ExtensionUtility::configurePlugin(
    'CkFaq',
    'Pi1',
    [
        FaqController::class => ['list'],
    ],
    [
        FaqController::class => ['list'],
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'CkFaq',
    'RatingApi',
    [
        FaqController::class => ['updateRating'],
    ],
    [
        FaqController::class => ['updateRating'],
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
