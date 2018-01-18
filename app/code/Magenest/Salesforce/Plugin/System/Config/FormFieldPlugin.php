<?php
namespace Magenest\Salesforce\Plugin\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field as ConfigFormField;
use Magento\Framework\App\RequestInterface;

class FormFieldPlugin
{

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * FormFieldPlugin constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param ConfigFormField $subject
     * @param AbstractElement $element
     */
    public function beforeRender(ConfigFormField $subject, AbstractElement $element)
    {
        $sectionName = $this->request->getParam('section');
        if ($sectionName == 'salesforcecrm') {
            $originalData = $element->getOriginalData();
            $htmlAttributes = $element->getHtmlAttributes();
            foreach ($htmlAttributes as $attribute) {
                if (isset($originalData[$attribute])) {
                    $element->addCustomAttribute($attribute, $originalData[$attribute]);
                }
            }
        }
    }
}
