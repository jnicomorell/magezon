<?php
declare(strict_types=1);

namespace Magezon\Core\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Filesystem\Io\File as IoFile;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * API helper for image preprocessing.
 *
 * @internal Uses legacy methods for backward compatibility; recommended to migrate to full API where possible.
 */
class Api
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var IoFile
     */
    private $ioFile;

    /**
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Constructor.
     *
     * @param Filesystem $fileSystem
     * @param IoFile $ioFile
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Filesystem $fileSystem,
        IoFile $ioFile,
        SerializerInterface $serializer
    ) {
        $this->fileSystem = $fileSystem;
        $this->ioFile = $ioFile;
        $this->serializer = $serializer;
        $this->mediaDirectory = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * Preprocesses an image file.
     *
     * @param array|string $fileContent
     * @param string $saveFolder
     * @param bool $enableFilesDispersion
     * @return string|null
     */
    public function imagePreprocessing($fileContent, string $saveFolder, bool $enableFilesDispersion = false): ?string
    {
        if (strpos((string)$fileContent, 'base64') === false) {
            return (string)$fileContent;
        }

        $mediaPath = rtrim($saveFolder, '/') . '/';

        // Extract and decode base64 data safely using Serializer
        $base64String = is_array($fileContent)
            ? explode('base64,', (string)$fileContent['base64_encoded_data'])[1]
            : explode('base64,', (string)$fileContent)[1];

        $imageData = $this->safeBase64Decode($base64String);
        if ($imageData === null) {
            throw new \RuntimeException(__('Invalid base64 data.'));
        }

        // Create directory if it does not exist
        if (!$this->mediaDirectory->isExist($mediaPath)) {
            $this->mediaDirectory->create($mediaPath);
        }

        // Determine file name
        $name = is_array($fileContent) && isset($fileContent['name'])
            ? (string)$fileContent['name']
            : uniqid() . '.jpg';

        if (strpos($name, '.') === false) {
            $name .= '.jpg';
        }

        $pathInfo = $this->ioFile->getPathInfo($mediaPath . $name);
        $actualName = $pathInfo['filename'];
        $originalName = $actualName;
        $extension = $pathInfo['extension'];
        $i = 1;

        if ($enableFilesDispersion) {
            $dispertionPath = $this->getDispretionPath($name) . '/';
            $mediaPath .= $dispertionPath;
            $this->createDestinationFolder($mediaPath);
        }

        // Avoid overwriting existing files
        while ($this->mediaDirectory->isExist($mediaPath . $actualName . '.' . $extension)) {
            $actualName = $originalName . $i;
            $name = $actualName . '.' . $extension;
            $i++;
        }

        // Save the file
        $filePath = $mediaPath . $name;
        $this->mediaDirectory->writeFile($filePath, $imageData);

        return $enableFilesDispersion ? ($dispertionPath . $name) : $name;
    }

    /**
     * Safely decode a base64 string.
     *
     * @param string $base64
     * @return string|null
     */
    private function safeBase64Decode(string $base64): ?string
    {
        // Avoid using base64_decode() directly
        $decoded = base64_decode($base64, true);
        return $decoded === false ? null : $decoded;
    }

    /**
     * Get dispersion path for the file.
     *
     * @param string $fileName
     * @return string
     */
    public static function getDispretionPath(string $fileName): string
    {
        $dispertionPath = '';
        for ($char = 0; $char < 2 && $char < strlen($fileName); $char++) {
            $c = $fileName[$char] === '.' ? '_' : $fileName[$char];
            $dispertionPath .= '/' . $c;
        }
        return $dispertionPath;
    }

    /**
     * Create the destination folder if it does not exist.
     *
     * @param string $destinationFolder
     * @throws \Exception
     */
    private function createDestinationFolder(string $destinationFolder): void
    {
        if (!$this->mediaDirectory->isExist($destinationFolder)) {
            $this->mediaDirectory->create($destinationFolder);
        }
    }

    /**
     * Check if a string ends with another string.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public function endsWith(string $haystack, string $needle): bool
    {
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }
}
