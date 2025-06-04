<?php
namespace Magezon\Builder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data;
use Magezon\Builder\Block\Element;

class SocialIcons implements ArgumentInterface
{
    protected $helper;
    protected $element;

    public function __construct(Data $helper, Element $element)
    {
        $this->helper = $helper;
        $this->element = $element;
    }

    public function getElement()
    {
        return $this->element->getElement();
    }

    public function getHtmlId()
    {
        return $this->getElement()->getHtmlId();
    }

    public function getItems()
    {
        $items = $this->getElement()->getItems();
        return is_array($items) ? array_map([$this, 'toObject'], $items) : [];
    }

    public function getLink($link)
    {
        return $this->helper->filter($link ?: '#');
    }

    public function getSocialLabel($icon)
    {
        // lógica de reemplazo simple
        return ucfirst(str_replace(['fa fa-', 'fab fa-'], '', $icon));
    }

    protected function toObject($array)
    {
        return is_array($array) ? (object)$array : $array;
    }

    public function getLinkTarget()
    {
        return $this->getElement()->getData('link_target');
    }

    public function showFollowButton()
    {
        return $this->getElement()->getData('follow_button');
    }
}
