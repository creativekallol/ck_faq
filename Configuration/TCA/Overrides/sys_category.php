<?php

declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$additionalColumns = [
    'slug' => [
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
        'config' => [
            'type' => 'slug',
            'size' => 50,
            'generatorOptions' => [
                'fields' => ['title'],
            ],
            'fallbackCharacter' => '-',
            'eval' => 'uniqueInSite',
            'default' => '',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('sys_category', $additionalColumns);
ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'slug',
    '',
    'after:title'
);
