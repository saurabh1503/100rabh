<?php

namespace Vsourz\Exitscreen\Model;

class Page extends \Magento\Cms\Model\Config\Source\Page
{

    protected $options;

    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->collectionFactory->create()->toOptionIdArray();
        }
        $this->options[] = array('value' => 'catalog', 'label' => 'Catalog');
        $this->options[] = array('value' => 'checkout', 'label' => 'Checkout');
        return $this->options;
    }

}
