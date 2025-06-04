<?php
declare(strict_types=1);

namespace Magezon\Core\Block\Product;

use Magento\Catalog\Helper\ImageFactory as HelperFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Image\NotLoadInfoImageException;

/**
 * Image builder block for product images.
 */
class ImageBuilder
{
    /**
     * @var int|null
     */
    protected $_imageWidth;

    /**
     * @var int|null
     */
    protected $_imageHeight;

    /**
     * @var int|null
     */
    protected $_resizeImageWidth;

    /**
     * @var int|null
     */
    protected $_resizeImageHeight;

    /**
     * @var \Magento\Catalog\Block\Product\ImageFactory
     */
    protected $imageFactory;

    /**
     * @var HelperFactory
     */
    protected $helperFactory;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var string|null
     */
    protected $imageId;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Constructor.
     *
     * @param HelperFactory $helperFactory
     * @param \Magento\Catalog\Block\Product\ImageFactory $imageFactory
     */
    public function __construct(
        HelperFactory $helperFactory,
        \Magento\Catalog\Block\Product\ImageFactory $imageFactory
    ) {
        $this->helperFactory = $helperFactory;
        $this->imageFactory = $imageFactory;
    }

    /**
     * Set product.
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Set image ID.
     */
    public function setImageId(string $imageId): self
    {
        $this->imageId = $imageId;
        return $this;
    }

    /**
     * Set custom attributes.
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Retrieve custom attributes for HTML element.
     */
    protected function getCustomAttributes()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
        if (version_compare($productMetadata->getVersion(), '2.4.0', '<')) {
            $result = [];
            foreach ($this->attributes as $name => $value) {
                $result[] = $name . '="' . $value . '"';
            }
            return !empty($result) ? implode(' ', $result) : '';
        }
        return $this->attributes;
    }

    /**
     * Calculate image ratio.
     */
    protected function getRatio(\Magento\Catalog\Helper\Image $helper): float
    {
        $width = $helper->getWidth();
        $height = $helper->getHeight();
        return ($width && $height) ? $height / $width : 1;
    }

    public function setImageWidth($imageWidth): self
    {
        $this->_imageWidth = $imageWidth;
        return $this;
    }

    public function getImageWidth()
    {
        return $this->_imageWidth;
    }

    public function setImageHeight($imageHeight): self
    {
        $this->_imageHeight = $imageHeight;
        return $this;
    }

    public function getImageHeight()
    {
        return $this->_imageHeight;
    }

    public function setResizeImageWidth($resizeImageWidth): self
    {
        $this->_resizeImageWidth = $resizeImageWidth;
        return $this;
    }

    public function getResizeImageWidth()
    {
        return $this->_resizeImageWidth;
    }

    public function setResizeImageHeight($resizeImageHeight): self
    {
        $this->_resizeImageHeight = $resizeImageHeight;
        return $this;
    }

    public function getResizeImageHeight()
    {
        return $this->_resizeImageHeight;
    }

    /**
     * Create image block for product image.
     *
     * @param Product|null $product
     * @param string|null $imageId
     * @param array|null $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function create(Product $product = null, $imageId = null, array $attributes = null)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $attrs = [];

        if ($this->getImageWidth()) {
            $attrs['width'] = $this->getImageWidth();
        }
        if ($this->getImageHeight()) {
            $attrs['height'] = $this->getImageHeight();
        }

        /** @var \Magento\Catalog\Helper\Image $helper */
        $helper = $this->helperFactory->create()->init($this->product, $this->imageId, $attrs);

        $template = $helper->getFrame()
            ? 'Magento_Catalog::product/image.phtml'
            : 'Magento_Catalog::product/image_with_borders.phtml';

        $imagesize = $helper->getResizedImageInfo();
        $imageWidth = $this->getImageWidth() ?: $helper->getWidth();
        $imageHeight = $this->getImageHeight() ?: $helper->getHeight();

        $resizeImageWidth = $this->getResizeImageWidth() ?: (!empty($imagesize[0]) ? $imagesize[0] : $helper->getWidth());
        $resizeImageHeight = $this->getResizeImageHeight() ?: (!empty($imagesize[1]) ? $imagesize[1] : $helper->getHeight());

        $data = [
            'template' => $template,
            'image_url' => $helper->getUrl(),
            'width' => $imageWidth,
            'height' => $imageHeight,
            'label' => $helper->getLabel(),
            'ratio' => $this->getRatio($helper),
            'custom_attributes' => $this->getCustomAttributes(),
            'resized_image_width' => $resizeImageWidth,
            'resized_image_height' => $resizeImageHeight,
            'class' => 'product-image-photo'
        ];

        $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');

        if (version_compare($productMetadata->getVersion(), '2.3.0', '<')) {
            return $this->imageFactory->create(['data' => $data]);
        }

        // Using setData() on helper (magic method via DataObject)
        $helperBlock = $this->imageFactory->create($this->product, $this->imageId, $attrs);
        $helperBlock->setData($data); // <- setData() comes from DataObject, safe to use
        return $helperBlock;
    }
}
