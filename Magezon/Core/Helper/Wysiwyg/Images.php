<?php
declare(strict_types=1);

namespace Magezon\Core\Helper\Wysiwyg;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Escaper;

/**
 * Helper to build image HTML declaration for Wysiwyg editor.
 * Nota: Esta clase reemplaza el uso de \Magento\Cms\Helper\Wysiwyg\Images.
 */
class Images
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Escaper
     */
    private Escaper $escaper;

    /**
     * Constructor.
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Escaper $escaper
    ) {
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
    }

    /**
     * Prepare image URL for Wysiwyg editor.
     *
     * @param string $filename
     * @param bool $renderAsTag
     * @return string
     */
    public function getImageHtmlDeclaration(string $filename, bool $renderAsTag = false): string
    {
        $mediaUrl  = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $fileUrl   = $mediaUrl . ltrim($filename, '/');
        $mediaPath = str_replace($mediaUrl, '', $fileUrl);

        // Si se requiere devolver un <img> tag
        if ($renderAsTag) {
            return sprintf('<img src="%s" alt="" />', $this->escaper->escapeHtml($fileUrl));
        }

        return $mediaPath;
    }
}
