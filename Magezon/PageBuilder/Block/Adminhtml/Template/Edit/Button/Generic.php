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

namespace Magezon\PageBuilder\Block\Adminhtml\Template\Edit\Button;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Generic button class for Template Edit page.
 */
class Generic implements ButtonProviderInterface
{
    /**
     * URL Builder.
     *
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Authorization Interface.
     *
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var \Magezon\PageBuilder\Model\Template|null
     */
    private $currentTemplate;

    /**
     * Constructor.
     *
     * @param UrlInterface $urlBuilder
     * @param AuthorizationInterface $authorization
     * @param \Magezon\PageBuilder\Model\Template|null $currentTemplate
     */
    public function __construct(
        UrlInterface $urlBuilder,
        AuthorizationInterface $authorization,
        \Magezon\PageBuilder\Model\Template $currentTemplate = null
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->authorization = $authorization;
        $this->currentTemplate = $currentTemplate;
    }

    /**
     * Generate URL by route and parameters.
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData(): array
    {
        return [];
    }

    /**
     * Check permission for passed action.
     *
     * @param string $resourceId
     * @return bool
     */
    protected function isAllowedAction(string $resourceId): bool
    {
        return $this->authorization->isAllowed($resourceId);
    }

    /**
     * Retrieve current template instance.
     *
     * @return \Magezon\PageBuilder\Model\Template|null
     */
    public function getCurrentTemplate(): ?\Magezon\PageBuilder\Model\Template
    {
        return $this->currentTemplate;
    }

    /**
     * Get button attribute.
     *
     * @param array $params
     * @return array
     */
    public function getButtonAttribute(array $params = []): array
    {
        return [
            'mage-init' => [
                'Magento_Ui/js/form/button-adapter' => [
                    'actions' => [
                        [
                            'targetName' => 'mgzpagebuilder_template_form.mgzpagebuilder_template_form',
                            'actionName' => 'save',
                            'params' => $params
                        ]
                    ]
                ]
            ]
        ];
    }
}
