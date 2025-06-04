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

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;

class BlockList implements \Magezon\Builder\Model\Source\ListInterface
{
    /**
     * @var BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @param BlockRepositoryInterface $blockRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->blockRepository = $blockRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    public function getItem($id)
    {
        try {
            $block = $this->blockRepository->getById($id);
            return [
                'label' => $block->getTitle(),
                'value' => $block->getId()
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
                    ->setField('block_id')
                    ->setConditionType('in')
                    ->setValue($q)
                    ->create();
            } elseif (is_numeric($q)) {
                $filters[] = $this->filterBuilder
                    ->setField('block_id')
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

        $searchCriteria = $this->searchCriteriaBuilder
            ->addSortOrder('title', 'ASC')
            ->create();

        $result = $this->blockRepository->getList($searchCriteria);

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
