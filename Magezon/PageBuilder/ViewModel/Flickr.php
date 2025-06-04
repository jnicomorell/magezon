<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\PageBuilder\Block\Element\Flickr as FlickrBlock;

class Flickr implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private FlickrBlock $block
    ) {}

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function getFilteredTitle(): string
    {
        $title = $this->getElement()->getData('title');
        return $this->coreHelper->filter((string) $title);
    }

    public function getTitleTag(): string
    {
        $tag = $this->getElement()->getData('title_tag');
        return $tag ?: 'h2';
    }

    public function getFilteredDescription(): string
    {
        $description = $this->getElement()->getData('description');
        return $this->coreHelper->filter((string) $description);
    }

    public function isShowLine(): bool
    {
        return (bool) $this->getElement()->getData('show_line');
    }

    public function getSerializedOptions(): string
    {
        $element = $this->getElement();
        $options = [
            'apiKey'         => $element->getData('flickr_api_key'),
            'photosetId'     => $element->getData('flickr_album_id'),
            'showPhotoTitle' => $element->getData('show_photo_title'),
            'thumSize'       => $element->getData('thum_size')
        ];
        $maxItems = $element->getData('max_items');
        if ($maxItems) {
            $options['photosLimit'] = $maxItems;
        }
        return $this->coreHelper->serialize($options);
    }
}
