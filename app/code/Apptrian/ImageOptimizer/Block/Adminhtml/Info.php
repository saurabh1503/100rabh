<?php
/**
 * @category  Apptrian
 * @package   Apptrian_ImageOptimizer
 * @author    Apptrian
 * @copyright Copyright (c) 2017 Apptrian (http://www.apptrian.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License
 */
 
namespace Apptrian\ImageOptimizer\Block\Adminhtml;

use Magento\Framework\Data\Form\Element\AbstractElement;

class Info extends \Magento\Backend\Block\AbstractBlock implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Retrieve element HTML markup.
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element  = null;
        $logopath = 'https://www.apptrian.com/media/apptrian.gif';
        $html     = <<<HTML
<div style="background: url('$logopath') no-repeat scroll 15px 15px #f8f8f8; 
border:1px solid #ccc; min-height:100px; margin:5px 0; 
padding:15px 15px 15px 140px;">
<p>
<strong>Magento Online Stores &amp; Extensions</strong><br />
<a href="https://www.apptrian.com" target="_blank">Apptrian</a> 
offers a wide choice of products and services for your online business.
</p>
<p>
Website: <a href="https://www.apptrian.com" target="_blank">www.apptrian.com</a>
<br />Like, share and follow us on 
<a href="https://www.facebook.com/apptrian" target="_blank">Facebook</a>, 
<a href="https://plus.google.com/+ApptrianCom" target="_blank">Google+</a>, 
<a href="https://www.pinterest.com/apptrian" target="_blank">Pinterest</a>, and 
<a href="https://twitter.com/apptrian" target="_blank">Twitter</a>.<br />
If you have any questions send email at 
<a href="mailto:service@apptrian.com">service@apptrian.com</a>.
</p>
</div>
<div>
<p style="margin: 20px 0 20px 0;">
<strong>Products and services you might be interested in:</strong></p>
<a href="https://www.apptrian.com/facebook-pixel-for-magento" 
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/facebook-pixel.jpg" 
     alt="Facebook Pixel" style="border:1px solid #ccc;" />
</a>
<a href="https://www.apptrian.com/image-optimizer-for-magento" 
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/image-optimizer.jpg" 
     alt="Image Optimizer" style="border:1px solid #ccc;" />
</a>
<a href="https://www.apptrian.com/minify-html-css-js-for-magento" 
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/minify-html-css-js.jpg" 
     alt="Minify HTML CSS JS" style="border:1px solid #ccc;" />
</a>
<a href="https://www.apptrian.com/professional-magento-installation" 
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/professional-magento-installation.jpg" 
     alt="Professional Magento Installation" style="border:1px solid #ccc;" />
</a>
<a href="https://www.apptrian.com/quick-search-for-magento" 
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/quick-search.jpg" 
     alt="Quick Search" style="border:1px solid #ccc;" />
</a>
<a href="https://www.apptrian.com/responsive-product-slider-for-magento"
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/responsive-product-slider.jpg" 
     alt="Responsive Product Slider" style="border:1px solid #ccc;" />
</a>
<a href="https://www.apptrian.com/schema-org-microdata-for-magento"
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/schema-org-microdata-for-magento.jpg" 
     alt="Schema.org Microdata for Magento" style="border:1px solid #ccc;" />
</a>
<a href="https://www.apptrian.com/snippets-generator-for-magento" 
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/snippets-generator.jpg" 
     alt="Snippets Generator" style="border:1px solid #ccc;" />
</a>
<a href="https://www.apptrian.com/social-integrator-for-magento" 
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/social-integrator.jpg" 
     alt="Social Integrator" style="border:1px solid #ccc;" />
</a>
<a href="https://www.apptrian.com/subcategories-grid-list-for-magento" 
   target="_blank" style="margin: 0 20px 20px 0; display: inline-block;">
<img src="https://www.apptrian.com/media/subcategories-grid-list.jpg"
     alt="Subcategories Grid/List" style="border:1px solid #ccc;" />
</a>
</div>
HTML;
        return $html;
    }
}
