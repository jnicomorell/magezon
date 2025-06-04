<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs, please refer to https://www.magezon.com for more information.
 *
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

declare(strict_types=1);

namespace Magezon\PageBuilder\Block\Element;

use Magento\Cms\Model\Template\FilterProvider;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Static block element block for page builder.
 */
class StaticBlock extends \Magezon\Builder\Block\Element implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * Storage for used widgets.
     *
     * @var array
     */
    private static $widgetUsageMap = [];

    /**
     * Constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param FilterProvider $filterProvider
     * @param BlockRepositoryInterface $blockRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        FilterProvider $filterProvider,
        BlockRepositoryInterface $blockRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->filterProvider = $filterProvider;
        $this->blockRepository = $blockRepository;
    }

    /**
     * Prepare block text and determine whether block output enabled or not.
     *
     * @return $this
     */
    protected function _beforeToHtml(): self
    {
        parent::_beforeToHtml();

        $blockId = (string) $this->getElement()->getData('block_id');
        $blockHash = get_class($this) . $blockId;

        if (isset(self::$widgetUsageMap[$blockHash])) {
            return $this;
        }
        self::$widgetUsageMap[$blockHash] = true;

        if ($blockId) {
            $storeId = (int) $this->_storeManager->getStore()->getId();

            try {
                $block = $this->blockRepository->getById($blockId);
                if ($block->isActive()) {
                    $this->setText(
                        $this->filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent())
                    );
                }
            } catch (NoSuchEntityException $e) {
                // Block not found, do nothing
            }
        }

        unset(self::$widgetUsageMap[$blockHash]);
        return $this;
    }
}
