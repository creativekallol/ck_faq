<?php

declare(strict_types=1);

namespace Creativekallol\CkFaq\UserFunc;

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

/**
 * @author (c) 2025 Kallol Chakraborty <kallolchakraborty@hotmail.com>
 */
final class RecordTitleUserFunc
{
    /**
     * Build custom record title: "Title (Helpful - X, Not Helpful - Y)"
     *
     * @param array $parameters TCA parameters
     */
    public function getRecordTitle(array &$parameters): void
    {
        $record = $parameters['row'];
        $title = (string)($record['title'] ?? '');
        $helpful = (int)($record['count_helpful'] ?? 0);
        $notHelpful = (int)($record['count_not_helpful'] ?? 0);

        $parameters['title'] = sprintf(
            '%s (👍 %d, 👎 %d)',
            $title,
            $helpful,
            $notHelpful
        );
    }
}
