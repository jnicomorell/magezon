<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_Builder
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

namespace Magezon\Builder\Model\Source;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;

class PageList implements \Magezon\Builder\Model\Source\ListInterface
{
    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->pageRepository = $pageRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    public function getItem($id)
    {
        try {
            $page = $this->pageRepository->getById($id);
            return [
                'label' => $page->getTitle(),
                'value' => $page->getId()
            ];
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return [];
        }
    }

    public function getList($q = '', $field = '')
    {
        $filters = [];

        if ($q) {
            if (is_array($q)) {
                $filters[] = $this->filterBuilder
                    ->setField('page_id')
                    ->setConditionType('in')
                    ->setValue($q)
                    ->create();
            } elseif (is_numeric($q)) {
                $filters[] = $this->filterBuilder
                    ->setField('page_id')
                    ->setConditionType('eq')
                    ->setValue($q)
                    ->create();
            } else {
                $filters[] = $this->filterBuilder
                    ->setField('title')
                    ->setConditionType('like')
                    ->setValue('%' . $q . '%')
                    ->create();
            }
        }

        foreach ($filters as $filter) {
            $this->searchCriteriaBuilder->addFilters([$filter]);
        }

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $result = $this->pageRepository->getList($searchCriteria);

        $list = [];
        foreach ($result->getItems() as $item) {
            $list[] = [
                'label' => $item->getTitle(),
                'value' => $item->getId()
            ];
        }

        return $list;
    }
}
