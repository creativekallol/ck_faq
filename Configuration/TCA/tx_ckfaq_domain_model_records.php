<?php

use Creativekallol\CkFaq\UserFunc\RecordTitleUserFunc;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:ck_faq/Resources/Private/Language/locallang_db.xlf:tx_ckfaq_domain_model_records',
        'label' => 'title',
        'label_alt' => 'count_helpful, count_not_helpful',
        'label_userFunc' => RecordTitleUserFunc::class . '->getRecordTitle',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'searchFields' => 'title,product_description',
        'iconfile' => 'EXT:ck_faq/Resources/Public/Icons/Extension.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, description, category, count_helpful, count_not_helpful, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_ckfaq_domain_model_records',
                'foreign_table_where' => 'AND {#tx_ckfaq_domain_model_records}.{#pid}=###CURRENT_PID### AND {#tx_ckfaq_domain_model_records}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ck_faq/Resources/Private/Language/locallang_db.xlf:tx_ckfaq_domain_model_records.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ck_faq/Resources/Private/Language/locallang_db.xlf:tx_ckfaq_domain_model_records.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 10,
                'required' => true,
                'eval' => 'trim',
                'default' => '',
                'enableRichtext' => true,
            ],
        ],
        'category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ck_faq/Resources/Private/Language/locallang_db.xlf:tx_ckfaq_domain_model_records.category',
            'config' => [
                'type' => 'category',
                'relationship' => 'manyToMany',
                'treeConfig' => [
                    'appearance' => [
                        'expandAll' => false,
                        'showHeader' => false,
                    ],
                ],
            ],
        ],
        'count_helpful' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ck_faq/Resources/Private/Language/locallang_db.xlf:tx_ckfaq_domain_model_records.count_helpful',
            'config' => [
                'type' => 'number',
                'size' => 10,
                'default' => 0,
                'range' => [
                    'lower' => 0,
                    'upper' => 99999
                ]
            ]
        ],
        'count_not_helpful' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ck_faq/Resources/Private/Language/locallang_db.xlf:tx_ckfaq_domain_model_records.count_not_helpful',
            'config' => [
                'type' => 'number',
                'size' => 10,
                'default' => 0,
                'range' => [
                    'lower' => 0,
                    'upper' => 99999
                ]
            ]
        ],
    ],
];
