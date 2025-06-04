<?php
declare(strict_types=1);

namespace Magezon\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magezon\Core\Block\Product\ImageBuilder;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Exception\FileSystemException;

/**
 * Image helper for resizing and URL generation.
 */
class Image extends AbstractHelper
{
    private ImageBuilder $imageBuilder;
    private Filesystem $filesystem;
    private AdapterFactory $imageFactory;
    private StoreManagerInterface $storeManager;
    private ReadInterface $mediaRead;
    private WriteInterface $mediaWrite;

    public function __construct(
        Context $context,
        ImageBuilder $imageBuilder,
        Filesystem $filesystem,
        AdapterFactory $imageFactory,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->imageBuilder = $imageBuilder;
        $this->filesystem = $filesystem;
        $this->imageFactory = $imageFactory;
        $this->storeManager = $storeManager;
        $this->mediaRead = $filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->mediaWrite = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    }

    /**
     * Build product image.
     */
    public function resizeImage($product, $imageWidth = '', $imageHeight = '', $attributes = [], $imageId = 'category_page_grid')
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageWidth($imageWidth)
            ->setImageHeight($imageHeight)
            ->setResizeImageWidth($imageWidth)
            ->setResizeImageHeight($imageHeight)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    /**
     * Resize an image and generate the new URL.
     *
     * @param string $src
     * @param int $width
     * @param int $height
     * @param int $quality
     * @param string $dir
     * @param string $newName
     * @param bool $deleteIfExist
     * @return string
     * @throws FileSystemException
     */
    public function resize(
        string $src,
        int $width = 150,
        int $height = 0,
        int $quality = 80,
        string $dir = 'magezon/resized',
        string $newName = '',
        bool $deleteIfExist = false
    ): string {
        $dirPath = $dir . '/' . $width;
        $absPath = $this->mediaRead->getAbsolutePath($src);
        $resizedPath = $this->mediaRead->getAbsolutePath($dirPath . '/' . ($newName ?: $src));

        // Check file existence via API
        if ($this->mediaRead->isExist($src)) {
            $imageResize = $this->imageFactory->create();
            $imageResize->open($absPath);
            $imageResize->backgroundColor([255, 255, 255]);
            $imageResize->constrainOnly(true);
            $imageResize->keepTransparency(true);
            $imageResize->keepFrame(true);
            $imageResize->keepAspectRatio(true);
            $imageResize->quality($quality);

            if ($height > 0) {
                $imageResize->resize($width, $height);
            } else {
                $imageResize->resize($width);
            }

            // Create directory if not exists
            if (!$this->mediaWrite->isExist($dirPath)) {
                $this->mediaWrite->create($dirPath);
            }

            if (!$this->mediaRead->isExist($resizedPath) || $deleteIfExist) {
                $imageResize->save($resizedPath);
            }

            $fileName = $newName ?: $src;
            $resizedURL = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $dirPath . '/' . $fileName;
            return str_replace('//', '/', $resizedURL);
        }

        return '';
    }
}
