<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magento\Catalog\Helper\Product\Compare as CompareHelper;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Catalog\Model\Product;

class ProductGrid implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private CompareHelper $compareHelper,
        private WishlistHelper $wishlistHelper
    ) {
    }

    public function filter(string $text): string
    {
        return $this->coreHelper->filter($text);
    }

    public function filterCarouselLazyImage(string $html): string
    {
        return $this->coreHelper->filterCarouselLazyImage($html);
    }

    public function isWishlistAllowed(): bool
    {
        return $this->wishlistHelper->isAllow();
    }

    public function getComparePostDataParams(Product $product): string
    {
        return $this->compareHelper->getPostDataParams($product);
    }
}
