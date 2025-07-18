<?php

declare(strict_types=1);

namespace Creativekallol\CkFaq\ViewHelpers;

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

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * @author (c) 2025 Kallol Chakraborty <kallolchakraborty@hotmail.com>
 */
final class FaqRatingViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'faqId',
            'int',
            'The UID of the FAQ record to check rating cookie for',
            true
        );
    }

    public function render(): string
    {
        $faqId = (int)$this->arguments['faqId'];
        $cookieName = 'faq_rating_' . $faqId;

        if (empty($_COOKIE[$cookieName])) {
            return '';
        }

        $decoded = urldecode($_COOKIE[$cookieName]);
        $cookie = @unserialize($decoded);

        if (!is_array($cookie) || empty($cookie['rate'])) {
            return '';
        }

        return (string)$cookie['rate'];
    }
}
