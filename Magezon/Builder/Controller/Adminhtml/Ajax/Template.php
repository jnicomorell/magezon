<?php
namespace Magezon\Builder\Controller\Adminhtml\Ajax;

use Magento\Backend\App\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\HTTP\ClientInterface;
use Magento\Framework\Filesystem\Io\File;
use Magezon\Core\Helper\Data as CoreHelper;
use Magento\Framework\Controller\Result\JsonFactory;

class Template extends Action
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var File
     */
    protected $file;

    /**
     * @var CoreHelper
     */
    protected $coreHelper;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param Action\Context $context
     * @param Filesystem $filesystem
     * @param ClientInterface $client
     * @param File $file
     * @param CoreHelper $coreHelper
     * @param RequestInterface $request
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Action\Context $context,
        Filesystem $filesystem,
        ClientInterface $client,
        File $file,
        CoreHelper $coreHelper,
        RequestInterface $request,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
        $this->client = $client;
        $this->file = $file;
        $this->coreHelper = $coreHelper;
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    private function proccessImages($item, $result)
    {
        if (isset($result['images']) && is_array($result['images']) && isset($result['target']) && isset($result['mediaUrl'])) {
            $this->file->mkdir('wysiwyg/' . $result['target']);
            $mediaDir = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $exportPath = $mediaDir->getAbsolutePath('wysiwyg/' . $result['target'] . '/' . $item['id']) . '/';
            $this->file->mkdir($exportPath);
            $result['mediaUrl'] = str_replace('https://magezon.com', 'https://www.magezon.com', $result['mediaUrl']);
            foreach ($result['images'] as $image) {
                $path = $exportPath . $image;
                $this->client->get($result['mediaUrl'] . $image);
                $this->file->write($path, $this->client->getBody(), 0777);
            }
        }
    }

    public function execute()
    {
        $result = [];
        try {
            // Utiliza la instancia inyectada de RequestInterface
            $post = $this->request->getPostValue();
            if (isset($post['item']) && $post['item']) {
                $item = $post['item'];
                $this->client->get($item['file']);
                $content = $this->client->getBody();
                if ($content) {
                    $result = $this->coreHelper->unserialize($content);
                    $this->proccessImages($item, $result);
                    if (!isset($result['elements']) && isset($result['profile']) && isset($result['profile']['elements'])) {
                        $result['elements'] = $result['profile']['elements'];
                        unset($result['profile']);
                    }
                }
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
        } catch (\Exception $e) {
            $result['status'] = false;
            $result['message'] = __('Something went wrong while process preview template.');
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the template.'));
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}
