<?php
declare(strict_types=1);

namespace Magezon\Core\Block\Adminhtml\Product\Widget;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\CatalogWidget\Model\Rule;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\FormKey;

/**
 * Block for rendering product widget conditions in adminhtml.
 *
 * @api
 * @since 2.4.7
 */
class Conditions extends Template implements RendererInterface
{
    /**
     * @var Factory
     */
    protected $elementFactory;

    /**
     * @var AbstractElement
     */
    protected $element;

    /**
     * @var Rule
     */
    protected $rule;

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var string
     */
    protected $_template = 'Magento_CatalogWidget::product/widget/conditions.phtml';

    /**
     * Constructor.
     *
     * @param Context $context
     * @param Factory $elementFactory
     * @param Rule $rule
     * @param FormKey $formKey
     * @param array $data
     */
    public function __construct(
        Context $context,
        Factory $elementFactory,
        Rule $rule,
        FormKey $formKey,
        array $data = []
    ) {
        $this->elementFactory = $elementFactory;
        $this->rule = $rule;
        $this->formKey = $formKey;
        parent::__construct($context, $data);
    }

    /**
     * Render element HTML.
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $this->element = $element;
        return $this->toHtml();
    }

    /**
     * Retrieve the form element.
     *
     * @return AbstractElement
     */
    public function getElement(): AbstractElement
    {
        return $this->element;
    }

    /**
     * Get URL for adding new condition.
     *
     * @return string
     */
    public function getNewChildUrl(): string
    {
        return $this->getUrl(
            'catalog_widget/product_widget/conditions',
            [
                'form' => $this->getHtmlId(),
                'mgz_builder' => 1,
                'form_key' => $this->formKey->getFormKey()
            ]
        );
    }

    /**
     * Retrieve the HTML ID for the current form element.
     *
     * @return string
     */
    public function getHtmlId(): string
    {
        return (string)$this->getData('htmlid') ?: (string)$this->getElement()->getHtmlId();
    }

    /**
     * Get the current rule object.
     *
     * @return Rule
     */
    public function getCurrentRule(): Rule
    {
        return $this->rule;
    }

    /**
     * Generate HTML for conditions input using API-compliant approach.
     *
     * @return string
     */
    public function getInputHtml(): string
    {
        $rule = $this->getCurrentRule();
        $parameters = $this->getData('parameters');
        if ($parameters && isset($parameters['conditions'])) {
            $parameters['conditions'] = json_decode((string)$parameters['conditions'], true);
            $rule->loadPost($parameters);
        }

        /** @var AbstractElement $input */
        $input = $this->elementFactory->create('text');
        $input->setForm($this->element->getForm());
        $input->setName($this->element->getName());
        $input->setHtmlId($this->getHtmlId());
        $input->setValue(__('Conditions will be displayed here.'));
        $input->setReadonly(true);

        return $input->getElementHtml();
    }

    /**
     * Escape string for JavaScript context.
     *
     * @param mixed $string
     * @return string
     */
    public function escapeJs($string): string
    {
        return $this->_escaper->escapeJs($string);
    }
}
