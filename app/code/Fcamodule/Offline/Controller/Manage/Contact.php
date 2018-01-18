<?php

namespace Fcamodule\Offline\Controller\Manage;

use Magento\Framework\Controller\ResultFactory;

class Contact extends \Magento\Framework\App\Action\Action
{
    /**
     * Contact action
     *
     * @return void
     */
    public function execute()
    {
        $post = (array) $this->getRequest()->getPost();

        if (!empty($post)) {
            // Retrieve your form data
            $firstname   = $post['firstname'];
            $lastname    = $post['lastname'];
            $phone       = $post['phone'];
            $bookingTime = $post['bookingTime'];

            // Doing-something with...

            // Display the succes form validation message
            $this->messageManager->addSuccessMessage('Booking done !');

            // Redirect to your form page (or anywhere you want...)
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl('/offline/manage/contact');

            return $resultRedirect;
        }
        // Render the page 
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}