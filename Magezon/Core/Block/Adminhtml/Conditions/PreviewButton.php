<?php
declare(strict_types=1);

namespace Magezon\Core\Block\Adminhtml\Conditions;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Widget\Button;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class PreviewButton
 *
 * @package Magezon\Core\Block\Adminhtml\Conditions
 */
class PreviewButton extends Template
{
    /**
     * Template file
     *
     * @var string
     */
    protected $_template = 'Magezon_Core::conditions_preview_button.phtml';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * PreviewButton constructor.
     *
     * @param Template\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        DataPersistorInterface $dataPersistor,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Retrieve form name from DataPersistor
     *
     * @return string|null
     */
    public function getFormName(): ?string
    {
        return $this->dataPersistor->get('mgz_conditions_form_name');
    }

    /**
     * Generate HTML for preview button
     *
     * @return string
     */
    public function getButtonHtml(): string
    {
        /** @var \Magento\Backend\Block\Widget\Button $button */
        $button = $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Button::class);
        $button->setData([
            'id' => 'mgz_conditions_preview_btn',
            'label' => __('Preview Products'),
            'class' => 'save primary',
        ]);

        return $button->toHtml();
    }
}
