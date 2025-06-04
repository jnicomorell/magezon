<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magento\Catalog\Helper\Product\Compare as CompareHelper;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Catalog\Model\Product;

class SingleProductData implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private CompareHelper $compareHelper,
        private WishlistHelper $wishlistHelper
    ) {}

    public function filter(string $text): string
    {
        return $this->coreHelper->filter($text);
    }

    public function getComparePostData(Product $product): string
    {
        return $this->compareHelper->getPostDataParams($product);
    }

    public function isWishlistAllowed(): bool
    {
        return $this->wishlistHelper->isAllow();
    }
}
