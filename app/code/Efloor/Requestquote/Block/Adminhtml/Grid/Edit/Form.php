<?php
namespace Efloor\Requestquote\Block\Adminhtml\Grid\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * Class Constructor
     * 
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid_form');
        $this->setTitle(__('Requestquote Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        
        $model = $this->_coreRegistry->registry('requestquote_grid');

       
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('post_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Customer Requestquote'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'fullname',
            'text',
            ['name' => 'fullname', 'label' => __('Customer Name'), 'title' => __('Customer Name'), 'required' => true]
        );
        
         $fieldset->addField(
            'email',
            'text',
            ['name' => 'email', 'label' => __('Email'), 'title' => __('Email'), 'required' => true]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

       $fieldset->addField(
            'phone',
            'text',
            ['name' => 'phone', 'label' => __('Phone'), 'title' => __('Phone'), 'required' => true]
        );
       
        $fieldset->addField(
            'firstaddress',
            'text',
            ['name' => 'firstaddress', 'label' => __('Address1'), 'title' => __('firstaddress'), 'required' => true]
        );

        $fieldset->addField(
            'secondaddress',
            'text',
            ['name' => 'secondaddress', 'label' => __('Address2'), 'title' => __('secondaddress'), 'required' => false]
        );
        $fieldset->addField(
            'city',
            'text',
            ['name' => 'city', 'label' => __('City'), 'title' => __('city'), 'required' => true]
        );
        $fieldset->addField(
            'state',
            'text',
            ['name' => 'state', 'label' => __('State'), 'title' => __('state'), 'required' => true]
        );
        $fieldset->addField(
            'zip',
            'text',
            ['name' => 'zip', 'label' => __('Zip'), 'title' => __('zip'), 'required' => true]
        );
        $fieldset->addField(
            'country',
            'text',
            ['name' => 'country', 'label' => __('Country'), 'title' => __('country'), 'required' => true]
        );

       $fieldset->addField(
            'memo',
            'text',
            ['name' => 'memo', 'label' => __('Memo'), 'title' => __('memo'), 'required' => true]
        );
      $fieldset->addField(
            'best_discribe',
            'text',
            ['name' => 'best_discribe', 'label' => __('Best_discribe'), 'title' => __('best_discribe'), 'required' => true]
        );
       $fieldset->addField(
            'contactby',
            'text',
            ['name' => 'contactby', 'label' => __('Contactby'), 'title' => __('contactby'), 'required' => true]
        );
        $fieldset->addField(
            'product_description',
            'text',
            ['name' => 'product_description', 'label' => __('Product_description'), 'title' => __('product_description'), 'required' => false]
        );
       $fieldset->addField(
            'product_qty',
            'text',
            ['name' => 'product_qty', 'label' => __('product_qty'), 'title' => __('product_qty'), 'required' => false]
        );
        $fieldset->addField(
            'product_description1',
            'text',
            ['name' => 'product_description', 'label' => __('Product_description'), 'title' => __('product_description'), 'required' => false]
        );
       $fieldset->addField(
            'product_qty1',
            'text',
            ['name' => 'product_qty', 'label' => __('product_qty'), 'title' => __('product_qty'), 'required' => false]
        );
        
        
        
        
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}