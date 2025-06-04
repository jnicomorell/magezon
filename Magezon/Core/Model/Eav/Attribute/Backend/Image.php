<?php
/**
 * Magezon
 *
 * @category  Magezon
 * @package   Magezon_Core
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

namespace Magezon\Core\Model\Eav\Attribute\Backend;

use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem\DriverInterface;

class Image extends AbstractBackend
{
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    protected $rootDirectory;

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Framework\Filesystem $filesystem
     * @param DriverInterface $driver
     */
    public function __construct(
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\Filesystem $filesystem,
        DriverInterface $driver
    ) {
        $this->_backendUrl   = $backendUrl;
        $this->rootDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->driver        = $driver;
    }

    /**
     * Validate image attribute
     *
     * @param \Magento\Framework\DataObject $object
     * @return bool
     * @throws InputException
     */
    public function validate($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $value    = $object->getData($attrCode);
        if ($value && !$this->isImage($value)) {
            throw new InputException(
                __('File \'%1\' is not a valid image.', $value)
            );
        }
        return true;
    }

    /**
     * Check if file is an image
     *
     * @param array|string $fileInfo
     * @return bool
     */
    protected function isImage($fileInfo)
    {
        $filePath = $this->rootDirectory->getAbsolutePath($fileInfo);

        if ($this->driver->isExists($filePath)) {
            if (is_array($fileInfo)) {
                return isset($fileInfo['type']) && strstr($fileInfo['type'], 'image/');
            }

            if (!$this->rootDirectory->isReadable($this->rootDirectory->getRelativePath($fileInfo))) {
                return false;
            }

            $fileContent = $this->driver->fileGetContents($filePath);
            $imageInfo   = getimagesizefromstring($fileContent);

            if (!$imageInfo) {
                return false;
            }
        }

        return true;
    }
}
