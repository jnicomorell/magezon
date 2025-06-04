<?php
declare(strict_types=1);

namespace Magezon\Core\Helper;

use Magento\Cms\Model\Page;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Helper class for various data utilities and image handling.
 */
class Data extends AbstractHelper
{
    public const ICONV_CHARSET = 'UTF-8';

    private StoreManagerInterface $storeManager;
    private FilterProvider $filterProvider;
    private Page $cmsPage;
    private Json $serializer;
    private ProductMetadataInterface $productMetadata;
    private DataPersistorInterface $dataPersistor;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        Page $cmsPage,
        Json $serializer,
        ProductMetadataInterface $productMetadata,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->filterProvider = $filterProvider;
        $this->cmsPage = $cmsPage;
        $this->serializer = $serializer;
        $this->productMetadata = $productMetadata;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Filter content with Magento CMS block filters.
     */
    public function filter(string $str): string
    {
        $str = $this->decodeDirectiveImages($str);
        $storeId = (int) $this->storeManager->getStore()->getId();
        $filter = $this->filterProvider->getBlockFilter()->setStoreId($storeId);
        $variables = [];

        if ($this->cmsPage->getId()) {
            $variables['page'] = $this->cmsPage;
        }

        if ($category = $this->dataPersistor->get('current_category')) {
            $variables['category'] = $category;
        }

        if ($product = $this->dataPersistor->get('current_product')) {
            $variables['product'] = $product;
        }

        $filter->setVariables($variables);

        if (version_compare($this->productMetadata->getVersion(), '2.4.0', '>=')) {
            $filter->setStrictMode(false);
        }

        return $filter->filter($str);
    }

    /**
     * Decode directive images within HTML content.
     */
    private function decodeDirectiveImages(string $content): string
    {
        preg_match_all('/<img[\s\r\n]+.*?>/is', $content, $matches);
        $search = $replace = [];

        foreach ($matches[0] as $imgHTML) {
            $key = 'directive/___directive/';
            if (strpos($imgHTML, $key) !== false) {
                $srcKey = 'src="';
                $start = strpos($imgHTML, $srcKey) + strlen($srcKey);
                $end = strpos($imgHTML, '"', $start);
                if ($end > $start) {
                    $imgSrc = substr($imgHTML, $start, $end - $start);
                    $start = strpos($imgSrc, $key) + strlen($key);
                    $imgBase64 = substr($imgSrc, $start);
                    $decodedUrl = urldecode($imgBase64); // Simple decoding
                    $replaceHTML = str_replace($imgSrc, $decodedUrl, $imgHTML);
                    $search[] = $imgHTML;
                    $replace[] = $replaceHTML;
                }
            }
        }

        return str_replace($search, $replace, $content);
    }

    public function unserialize(string $string)
    {
        if ($this->isJSON($string)) {
            return $this->serializer->unserialize($string);
        }
        return $string;
    }

    public function serialize($array = []): string
    {
        return $this->serializer->serialize($array);
    }

    public function isJSON(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function getMediaUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function convertImageUrl(string $string): string
    {
        return str_replace($this->getMediaUrl(), '', $string);
    }

    public function startsWith(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    public function endsWith(string $haystack, string $needle): bool
    {
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }

    public function getImageUrl(string $string): string
    {
        if ($string && strpos($string, 'http') === false && strpos($string, '<div') === false) {
            $string = $this->getMediaUrl() . ltrim($string, '/');
        }
        return $string;
    }

    public function dataPreprocessing($data)
    {
        if (is_array($data)) {
            foreach ($data as &$row) {
                $row = $this->unserialize($row);
                if ($row === '1' || $row === '0') {
                    $row = (int) $row;
                }
                $row = $this->getImageUrl((string)$row);
                if (is_array($row)) {
                    $row = $this->dataPreprocessing($row);
                }
            }
        }
        return $data;
    }

    public function substr(string $string, int $length, bool $keepWords = true): string
    {
        $string = mb_convert_encoding($string, self::ICONV_CHARSET);
        if ($keepWords) {
            if (preg_match('/^.{1,' . $length . '}\b/s', $string, $match)) {
                return $match[0];
            }
        }
        return mb_substr($string, 0, $length, self::ICONV_CHARSET);
    }

    public function strlen(string $string): int
    {
        return mb_strlen($string, self::ICONV_CHARSET);
    }

    public function cleanStyle(string $html): string
    {
        return preg_replace('@<style class="mgz-style">.*?</style>@ms', '', $html);
    }

    // Métodos auxiliares para CSS
    public function getStyleColor(string $value, bool $isImportant = false): string
    {
        if ($value && !$this->startsWith($value, '#') && !$this->startsWith($value, 'rgb') && $value !== 'transparent') {
            $value = '#' . $value;
        }
        if ($value && $isImportant) {
            $value .= ' !important';
        }
        return $value;
    }

    public function getStyleProperty($value, bool $isImportant = false, string $unit = ''): string
    {
        if (is_numeric($value)) {
            $value .= $unit ?: 'px';
        }
        if ($value === '-') {
            $value = '';
        }
        if ($value && $isImportant) {
            $value .= ' !important';
        }
        return $value;
    }

    public function getResponsiveClass(int $number): int
    {
        return in_array($number, [1, 2, 3, 4, 6, 12], true) ? (12 / $number) : ($number === 5 ? 15 : $number);
    }
}
