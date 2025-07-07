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
class FaqService
{
    public function getFaqByDemand(array $demand = []): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_ckfaq_domain_model_records');

        $queryBuilder
            ->select(
                'r.uid',
                'r.title',
                'r.description'
            )
            ->from('tx_ckfaq_domain_model_records', 'r')
            ->leftJoin(
                'r',
                'sys_category_record_mm',
                'mm',
                'mm.uid_foreign = r.uid AND mm.tablenames = ' . $queryBuilder->createNamedParameter('tx_ckfaq_domain_model_records') . ' AND mm.fieldname = ' . $queryBuilder->createNamedParameter('category')
            )
            ->leftJoin(
                'mm',
                'sys_category',
                'c',
                'c.uid = mm.uid_local'
            );

        $context = GeneralUtility::makeInstance(Context::class);
        $languageUid = (int)$context->getPropertyFromAspect('language', 'id');

        $constraints = [
            $queryBuilder->expr()->eq('r.hidden', $queryBuilder->createNamedParameter(0)),
            $queryBuilder->expr()->eq('r.deleted', $queryBuilder->createNamedParameter(0)),
            $queryBuilder->expr()->or(
                $queryBuilder->expr()->eq('r.starttime', $queryBuilder->createNamedParameter(0)),
                $queryBuilder->expr()->lte('r.starttime', $queryBuilder->createNamedParameter(time()))
            ),
            $queryBuilder->expr()->or(
                $queryBuilder->expr()->eq('r.endtime', $queryBuilder->createNamedParameter(0)),
                $queryBuilder->expr()->gt('r.endtime', $queryBuilder->createNamedParameter(time()))
            ),
            $queryBuilder->expr()->eq('r.sys_language_uid', $queryBuilder->createNamedParameter($languageUid, Connection::PARAM_INT))
        ];

        if (!empty($demand['pid'])) {
            $constraints[] = $queryBuilder->expr()->eq('r.pid', $queryBuilder->createNamedParameter((int)$demand['pid'], Connection::PARAM_INT));
        }

        if (!empty($demand['category'])) {
            $constraints[] = $queryBuilder->expr()->eq('c.uid', $queryBuilder->createNamedParameter((int)$demand['category']));
        }

        if (!empty($demand['term'])) {
            $likeTerm = '%' . $queryBuilder->escapeLikeWildcards($demand['term']) . '%';
            $constraints[] = $queryBuilder->expr()->or(
                $queryBuilder->expr()->like('r.title', $queryBuilder->createNamedParameter($likeTerm)),
                $queryBuilder->expr()->like('r.description', $queryBuilder->createNamedParameter($likeTerm))
            );
        }

        $queryBuilder
            ->where(...$constraints)
            ->orderBy('r.count_helpful', 'DESC')
            ->addOrderBy('r.count_not_helpful', 'ASC')
            ->addOrderBy('r.tstamp', 'DESC');

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }

    public function updateRating(int $faqId, string $activity): void
    {
        if (!in_array($activity, ['i', 'd'], true)) {
            return;
        }

        $field = $activity === 'i' ? 'count_helpful' : 'count_not_helpful';

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_ckfaq_domain_model_records');

        $queryBuilder
            ->update('tx_ckfaq_domain_model_records')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($faqId, Connection::PARAM_INT))
            )
            ->set($field, $queryBuilder->quoteIdentifier($field) . ' + 1', false)
            ->executeStatement();
    }
}
