<?php

declare(strict_types=1);

namespace Creativekallol\CkFaq\Service;

/* * *************************************************************
 *
 *  Copyright notice
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author (c) 2025 Kallol Chakraborty <kallolchakraborty@hotmail.com>
 */
class CategoryService
{
    public function getCategories(int $parent = 0): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');

        $context = GeneralUtility::makeInstance(Context::class);
        $languageUid = (int)$context->getPropertyFromAspect('language', 'id');
        $now = time();

        $queryBuilder
            ->select('uid', 'title')
            ->from('sys_category')
            ->where(
                $queryBuilder->expr()->eq('hidden', $queryBuilder->createNamedParameter(0)),
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0)),
                $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($languageUid, Connection::PARAM_INT)),
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('starttime', $queryBuilder->createNamedParameter(0)),
                    $queryBuilder->expr()->lte('starttime', $queryBuilder->createNamedParameter($now))
                ),
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('endtime', $queryBuilder->createNamedParameter(0)),
                    $queryBuilder->expr()->gt('endtime', $queryBuilder->createNamedParameter($now))
                )
            )
            ->orderBy('sorting', 'ASC');

        if ($parent > 0) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('parent', $queryBuilder->createNamedParameter($parent, Connection::PARAM_INT))
            );
        }

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }
}
