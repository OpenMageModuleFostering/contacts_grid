<?php

class Buzzzaky_Contactsgrid_Adminhtml_ContactsgridController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu("contactsgrid/contactsgrid")->_addBreadcrumb(Mage::helper("adminhtml")->__("Contactsgrid  Manager"), Mage::helper("adminhtml")->__("Contactsgrid Manager"));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__("Contactsgrid"));
        $this->_title($this->__("Manager Contact Us Grid"));

        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction() {
        $this->_title($this->__("Contact Us Grid"));
        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("contactsgrid/contactsgrid")->load($id);
        if ($model->getId()) {
            Mage::register("contactsgrid_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("contactsgrid/contactsgrid");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Contactsgrid Manager"), Mage::helper("adminhtml")->__("Contactsgrid Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Contactsgrid Description"), Mage::helper("adminhtml")->__("Contactsgrid Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("contactsgrid/adminhtml_contactsgrid_edit"))->_addLeft($this->getLayout()->createBlock("contactsgrid/adminhtml_contactsgrid_edit_tabs"));
            if (!$model->getStatus())
                $model->setStatus(Buzzzaky_Contactsgrid_Model_Contactsgrid::STATUS_READ);
            $model->save();
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("contactsgrid")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function saveAction() {
        $post_data = $this->getRequest()->getPost();
        $model = Mage::getModel("contactsgrid/contactsgrid");
        $collection = $model->getCollection()->addFieldToFilter('id', array('eq' => $post_data['id']))->getData();
        if ($post_data) {
            try {
                $data = array();
                $data['reply_date'] = time();
                $data['reply'] = $post_data["reply"];
                $data['status'] = Buzzzaky_Contactsgrid_Model_Contactsgrid::STATUS_REPLIED;
                $model->load($post_data['id']);
                $model->addData($data);
                $model->save();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::getStoreConfig('cntactsgrid/contactus/send_sucess_message'));
                Mage::getSingleton("adminhtml/session")->setContactsgridData(false);
                $this->sendReplyEmail($collection[0], $post_data["reply"]);
                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setContactsgridData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                return;
            }
        }
        $this->_redirect("*/*/");
    }

    public function sendReplyEmail($emailData, $reply) {
        $commentHistory = '<p>...</p><p> On ' . Mage::helper('core')->formatDate($emailData['comment_date'], 'full', false)
                . ' at ' . date('h:i A', $emailData['comment_date'])
                . ', ' . $emailData['name'] . ' &lt' . $emailData['email'] . '&gt ' . ' wrote:</p>';
        $html = $reply . $commentHistory . $emailData['comment'];
        $mail = Mage::getModel('core/email');
        $mail->setToName($emailData['name']);
        $mail->setToEmail($emailData['email']);
        $mail->setBody($html);
        $mail->setSubject(Mage::getStoreConfig('cntactsgrid/contactus/subject'));
        $mail->setFromEmail(Mage::getStoreConfig('cntactsgrid/contactus/sender_email'));
        $mail->setFromName(Mage::getStoreConfig('cntactsgrid/contactus/sender_name'));
        $mail->setType('html'); // YOu can use Html or text as Mail format
        $mail->send();
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam("id") > 0) {
            try {
                $model = Mage::getModel("contactsgrid/contactsgrid");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }

    public function massRemoveAction() {
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("contactsgrid/contactsgrid");
                $model->setId($id)->delete();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    public function readAllAction() {
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("contactsgrid/contactsgrid")->load($id);
                if (!$model->getStatus())
                    $model->setStatus(Buzzzaky_Contactsgrid_Model_Contactsgrid::STATUS_READ);
                $model->save();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully marked as read"));
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'contactsgrid.csv';
        $grid = $this->getLayout()->createBlock('contactsgrid/adminhtml_contactsgrid_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction() {
        $fileName = 'contactsgrid.xml';
        $grid = $this->getLayout()->createBlock('contactsgrid/adminhtml_contactsgrid_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

}
