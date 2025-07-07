<?php

declare(strict_types=1);

namespace Creativekallol\CkFaq\Controller;

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

use Creativekallol\CkFaq\Service\CategoryService;
use Creativekallol\CkFaq\Service\FaqService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * @author (c) 2025 Kallol Chakraborty <kallolchakraborty@hotmail.com>
 */
class FaqController extends ActionController
{
    protected CategoryService $categoryService;
    protected FaqService $faqService;
    protected int $itemsPerPage = 5;
    protected int $maximumLinks = 3;

    public function __construct(CategoryService $categoryService, FaqService $faqService)
    {
        $this->categoryService = $categoryService;
        $this->faqService = $faqService;
    }

    public function listAction(): ResponseInterface
    {
        $storagePid = (int)($this->settings['storagePid'] ?? 0);
        $categoryParent = (int)($this->settings['categoryParent'] ?? 0);
        $itemsPerPage = max((int)($this->settings['itemsPerPage'] ?? 0), $this->itemsPerPage);
        $currentPage = $this->request->hasArgument('currentPage') ? (int)$this->request->getArgument('currentPage') : 1;

        $demand = ['category' => '', 'term' => '', 'pid' => $storagePid];

        if ($this->request->hasArgument('category')) {
            $demand['category'] = (int)($this->request->getArgument('category') ?? 0);
        }

        if ($this->request->hasArgument('term')) {
            $demand['term'] = trim((string)($this->request->getArgument('term') ?? ''));
        }

        $faqs = $this->faqService->getFaqByDemand($demand);
        $paginator = new ArrayPaginator($faqs, $currentPage, $itemsPerPage);
        $pagination = new SlidingWindowPagination($paginator, $this->maximumLinks);

        $this->view->assignMultiple([
            'categories' => $this->categoryService->getCategories($categoryParent),
            'pagination' => $pagination,
            'demand' => $demand,
            'faqCount' => count($faqs),
            'currentPage' => $currentPage,
        ]);

        return $this->htmlResponse();
    }

    public function updateRatingAction(): ResponseInterface
    {
        $postData = $this->request->getParsedBody();

        $act = $postData['act'] ?? '';
        $faqId = (int)($postData['faq'] ?? 0);

        if (empty($act) || empty($faqId) || !in_array($act, ['i', 'd'], true)) {
            throw new \InvalidArgumentException('Invalid rating data', 1580585107);
        }

        $cookieName = 'faq_rating_' . $faqId;

        // Exit early if already rated
        if (!empty($_COOKIE[$cookieName])) {
            $response = $this->responseFactory->createResponse()
                ->withHeader('Content-Type', 'application/json; charset=utf-8');
            $response->getBody()->write(
                json_encode(['result' => false], JSON_THROW_ON_ERROR)
            );
            return $response;
        }

        // DB update
        $this->faqService->updateRating($faqId, $act);

        // Set rating cookie (expires in 10 years)
        $ratingData = [
            'faq' => $faqId,
            'rate' => $act
        ];
        $cookieValue = urlencode(serialize($ratingData));
        $cookieExpire = time() + (10 * 365 * 24 * 60 * 60); // 10 years

        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withAddedHeader('Set-Cookie', sprintf(
                '%s=%s; Expires=%s; Path=/; SameSite=Lax',
                $cookieName,
                $cookieValue,
                gmdate('D, d-M-Y H:i:s T', $cookieExpire)
            ));

        $response->getBody()->write(
            json_encode(['result' => true], JSON_THROW_ON_ERROR)
        );

        return $response;
    }
}
