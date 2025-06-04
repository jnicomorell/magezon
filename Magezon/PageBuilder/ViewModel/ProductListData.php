<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Helper\Product\Compare as CompareHelper;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;

class ProductListData implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private CompareHelper $compareHelper,
        private WishlistHelper $wishlistHelper
    ) {
    }

    public function filter(?string $content): string
    {
        return $this->coreHelper->filter($content ?? '');
    }

    public function isWishlistAllowed(): bool
    {
        return $this->wishlistHelper->isAllow();
    }

    public function getComparePostData(Product $product): string
    {
        return $this->compareHelper->getPostDataParams($product);
    }
}
