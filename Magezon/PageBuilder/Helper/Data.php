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

namespace Magezon\PageBuilder\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magezon\Builder\Helper\Data as BuilderHelper;

/**
 * PageBuilder helper class.
 */
class Data
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var BuilderHelper
     */
    private $builderHelper;

    /**
     * Constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param BuilderHelper $builderHelper
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        BuilderHelper $builderHelper
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->builderHelper = $builderHelper;
    }

    /**
     * Get config value.
     *
     * @param string $key
     * @param int|string|null $store
     * @return string|null
     */
    public function getConfig(string $key, $store = null): ?string
    {
        $store = $this->storeManager->getStore($store);
        return $this->scopeConfig->getValue(
            'mgzpagebuilder/' . $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Check if module is enabled.
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return (bool) $this->getConfig('general/enable');
    }

    /**
     * Get HTML for a profile.
     *
     * @param string $profile
     * @return string
     */
    public function getProfileHtml(string $profile): string
    {
        return $this->builderHelper->prepareProfileBlock(
            \Magezon\PageBuilder\Block\Profile::class,
            $profile
        )->toHtml();
    }

    /**
     * Filter content and replace profile shortcodes.
     *
     * @param string $value
     * @return string
     */
    public function filter(string $value): string
    {
        if ($value) {
            $key = $this->getKey();
            $prex = '/\[' . $key . '\](.*?)\[\/' . $key . '\]/si';
            preg_match_all($prex, $value, $matches, PREG_SET_ORDER);

            if ($matches) {
                $search = $replace = [];
                foreach ($matches as $row) {
                    $search[] = $row[0];
                    $replace[] = $this->getProfileHtml($row[1]);
                }
                $value = str_replace($search, $replace, $value);
            }
        }
        return $value;
    }

    /**
     * Get shortcode key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return 'mgz_pagebuilder';
    }
}
