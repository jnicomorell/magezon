<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magento\Wishlist\Helper\Data as WishlistHelper;

class ProductSlider implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private WishlistHelper $wishlistHelper
    ) {}

    public function filter(string $text): string
    {
        return $this->coreHelper->filter($text);
    }

    public function filterCarouselLazyImage(string $html): string
    {
        return $this->coreHelper->filterCarouselLazyImage($html);
    }

    public function serialize(array $data): string
    {
        return $this->coreHelper->serialize($data);
    }

    public function isWishlistAllowed(): bool
    {
        return $this->wishlistHelper->isAllow();
    }
}
