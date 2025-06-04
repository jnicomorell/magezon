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

namespace Magezon\PageBuilder\Controller\Adminhtml\Builder;

use Magento\Backend\App\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutFactory;

/**
 * Controller for loading the page builder preview.
 */
class Load extends Action
{
    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Constructor.
     *
     * @param Action\Context $context
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     * @param RequestInterface $request
     */
    public function __construct(
        Action\Context $context,
        RawFactory $resultRawFactory,
        LayoutFactory $layoutFactory,
        RequestInterface $request
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->request = $request;
    }

    /**
     * Execute the load action.
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $block = $this->layoutFactory->create()->createBlock(\Magezon\PageBuilder\Block\Builder::class);
        $block->addData($this->request->getPostValue());
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($block->toHtml());
    }
}
