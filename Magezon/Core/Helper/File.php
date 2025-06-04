<?php
declare(strict_types=1);

namespace Magezon\Core\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem\Io\File as IoFile;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Helper for secure file upload and removal.
 */
class File extends AbstractHelper
{
    private Filesystem $filesystem;
    private RequestInterface $request;
    private UploaderFactory $uploaderFactory;
    private IoFile $ioFile;

    public function __construct(
        Context $context,
        Filesystem $filesystem,
        RequestInterface $request,
        UploaderFactory $uploaderFactory,
        IoFile $ioFile
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
        $this->request = $request;
        $this->uploaderFactory = $uploaderFactory;
        $this->ioFile = $ioFile;
    }

    /**
     * Upload image or delete it based on the data provided.
     *
     * @param string $type
     * @param array $data
     * @param string $mediaFolder
     * @param array $allowedExtensions
     * @param int $maximumSize
     * @return array
     * @throws ValidatorException
     */
    public function uploadImage(
        string $type,
        array $data,
        string $mediaFolder,
        array $allowedExtensions = [],
        int $maximumSize = 0
    ): array {
        /** @var WriteInterface $mediaDirectory */
        $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);

        // Delete the existing image if requested
        if (isset($data[$type]['delete']) && $data[$type]['delete']) {
            $filePath = $mediaDirectory->getAbsolutePath($data[$type]['value'] ?? '');
            if ($mediaDirectory->isExist($filePath) && $filePath !== $mediaDirectory->getAbsolutePath()) {
                $mediaDirectory->delete($filePath);
            }
            $data[$type] = '';
        }

        // Process the uploaded image
        $image = $this->request->getFiles($type);
        if (isset($image['error']) && $image['error'] === 0) {
            if ($maximumSize > 0 && $image['size'] > $maximumSize) {
                throw new ValidatorException(__('The file is too large. Maximum upload file size: %1MB', $maximumSize / 1000000));
            }

            $savePath = $mediaDirectory->getAbsolutePath($mediaFolder);
            $uploader = $this->uploaderFactory->create(['fileId' => $type]);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);

            if (!empty($allowedExtensions)) {
                $uploader->setAllowedExtensions($allowedExtensions);
            }

            $result = $uploader->save($savePath);

            $filePath = $mediaFolder . '/' . $result['name'];
            $filePath = str_replace('//', '/', $filePath);
            $data[$type] = $filePath;
        } elseif (isset($data[$type]) && is_array($data[$type])) {
            $data[$type] = $data[$type]['value'];
        }

        return $data;
    }
}
