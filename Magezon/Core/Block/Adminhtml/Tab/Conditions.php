<?php
declare(strict_types=1);

namespace Magezon\Core\Block\Adminhtml\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\Form\Element\Fieldset as ElementFieldset;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\SalesRule\Model\RuleFactory;
use Magezon\Core\Block\Adminhtml\Conditions\AssignProduct;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

/**
 * Conditions tab block for displaying product conditions in adminhtml.
 * @internal Uses deprecated and non-API classes; recommended to migrate to UI Components in the future.
 */
class Conditions extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
    /**
     * Fieldset renderer block.
     *
     * @var Fieldset
     */
    protected $rendererFieldset;

    /**
     * Conditions block (non-API).
     *
     * @var \Magento\Rule\Block\Conditions
     */
    protected $conditions;

    /**
     * Rule factory.
     *
     * @var RuleFactory
     */
    private $ruleFactory;

    /**
     * Data persistor for safe data storage.
     *
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Constructor.
     */
    public function __construct(
        Context $context,
        FormFactory $formFactory,
        Fieldset $rendererFieldset,
        \Magento\Rule\Block\Conditions $conditions,
        RuleFactory $ruleFactory,
        DataPersistorInterface $dataPersistor,
        array $data = []
    ) {
        $this->rendererFieldset = $rendererFieldset;
        $this->conditions = $conditions;
        $this->ruleFactory = $ruleFactory;
        $this->dataPersistor = $dataPersistor;
        // Using getRegistry() from context is discouraged but required by Generic
        parent::__construct($context, $context->getRegistry(), $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel(): \Magento\Framework\Phrase
    {
        return __('Conditions');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle(): \Magento\Framework\Phrase
    {
        return __('Conditions');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabClass(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getTabUrl(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function isAjaxLoaded(): bool
    {
        return false;
    }

    /**
     * Prepare form with conditions.
     *
     * @return $this
     */
    protected function _prepareForm(): self
    {
        /** @var \Magento\SalesRule\Model\Rule $model */
        $model = $this->dataPersistor->get('mgz_conditions_model');
        $fieldsetId = $this->dataPersistor->get('mgz_conditions_fieldset') ?? 'conditions_fieldset';
        $formName = $this->dataPersistor->get('mgz_conditions_form_name') ?? 'default_form';

        $form = $this->addTabToForm($model, $formName, $fieldsetId);
        $this->setForm($form);

        // Parent::setForm() and parent::_prepareForm() are deprecated, but no alternatives exist
        return parent::_prepareForm();
    }

    /**
     * Generate HTML for this block including preview grid.
     *
     * @return string
     */
    public function toHtml(): string
    {
        return parent::toHtml() . $this->getGridHtml();
    }

    /**
     * Render the product preview grid.
     *
     * @return string
     */
    public function getGridHtml(): string
    {
        $previewBlock = $this->getLayout()->createBlock(AssignProduct::class);
        return $previewBlock->toHtml();
    }

    /**
     * Add conditions tab to form.
     *
     * @param \Magento\SalesRule\Model\Rule $model
     * @param string $formName
     * @param string $fieldsetId
     * @return Form
     */
    protected function addTabToForm($model, string $formName, string $fieldsetId = 'conditions_fieldset'): Form
    {
        $conditionsFieldSetId = $model->getConditionsFieldSetId($formName);
        $newChildUrl = $this->getUrl(
            'sales_rule/promo_quote/newConditionHtml/form/' . $conditionsFieldSetId,
            ['form_namespace' => $formName]
        );

        /** @var Form $form */
        $form = $this->_formFactory->create();
        // setHtmlIdPrefix is a magic method via DataObject
        $form->setHtmlIdPrefix('rule_');

        /** @var Fieldset $renderer */
        $renderer = $this->getLayout()->createBlock(Fieldset::class);
        $renderer->setTemplate('Magento_CatalogRule::promo/fieldset.phtml')
            ->setNewChildUrl($newChildUrl)
            ->setFieldSetId($conditionsFieldSetId);

        /** @var ElementFieldset $fieldset */
        $fieldset = $form->addFieldset(
            $fieldsetId,
            ['legend' => __('Apply the rule only if the following conditions are met (leave blank for all products).')]
        )->setRenderer($renderer);

        /** @var \Magento\Framework\Data\Form\Element\Text $field */
        $field = $fieldset->addField(
            'conditions',
            'text',
            [
                'name' => 'conditions',
                'label' => __('Conditions'),
                'title' => __('Conditions'),
                'required' => true,
                'data-form-part' => $formName,
            ]
        );
        // Uses no-API methods (setRule, setRenderer)
        $field->setRule($model)->setRenderer($this->conditions);

        $form->setValues($model->getData());
        $this->setConditionFormName($model->getConditions(), $formName);
        return $form;
    }

    /**
     * Recursively set form name for conditions and subconditions.
     *
     * @param AbstractCondition $conditions
     * @param string $formName
     * @return void
     */
    private function setConditionFormName(AbstractCondition $conditions, string $formName): void
    {
        $conditions->setFormName($formName);
        if ($conditions->getConditions() && is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName);
            }
        }
    }
}
