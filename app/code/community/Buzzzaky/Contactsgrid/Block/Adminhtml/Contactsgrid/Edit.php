<?php

class Buzzzaky_Contactsgrid_Block_Adminhtml_Contactsgrid_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();
        $this->_objectId = "id";
        $this->_blockGroup = "contactsgrid";
        $this->_controller = "adminhtml_contactsgrid";
        $this->_updateButton("save", "label", Mage::helper("contactsgrid")->__("Reply comment"));
        $this->_updateButton("delete", "label", Mage::helper("contactsgrid")->__("Delete Comment"));
        if ((int)Mage::registry("contactsgrid_data")->getStatus() === Buzzzaky_Contactsgrid_Model_Contactsgrid::STATUS_REPLIED) {
            $this->_removeButton(save);
        }
    }

    public function getHeaderText() {
        if (Mage::registry("contactsgrid_data") && Mage::registry("contactsgrid_data")->getId()) {
            return Mage::helper("contactsgrid")->__("Details of Contact Us item '%s'", $this->htmlEscape(Mage::registry("contactsgrid_data")->getId()));
        }
    }

}
