<?php

class Buzzzaky_Contactsgrid_Block_Adminhtml_Contactsgrid_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("contactsgrid_form", array("legend" => Mage::helper("contactsgrid")->__("Contact Us information")));

        $fieldset->addField("name", "label", array(
            "label" => Mage::helper("contactsgrid")->__("Name"),
            "name" => "name",
            "readonly" => true,
        ));

        $fieldset->addField("email", "label", array(
            "label" => Mage::helper("contactsgrid")->__("Email"),
            "name" => "email",
            "readonly" => true,
        ));

        $fieldset->addField("telephone", "label", array(
            "label" => Mage::helper("contactsgrid")->__("Telephone"),
            "name" => "telephone",
            "readonly" => true,
        ));

        $fieldset->addField("comment", "label", array(
            "label" => Mage::helper("contactsgrid")->__("Comment"),
            "name" => "comment",
            "readonly" => true,
        ));

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config');
        $fieldset->addField('reply', 'editor', array(
            'name' => 'reply',
            'label' => Mage::helper('contactsgrid')->__('Reply to comment'),
            'title' => Mage::helper('contactsgrid')->__('Reply to comment'),
            'style' => 'height: 500px;',
            'wysiwyg' => true,
            'required' => TRUE,
            'config' => $wysiwygConfig
        ));
        $fieldset->addField('id', 'hidden', array(
            'name' => 'id',
        ));
        if (Mage::getSingleton("adminhtml/session")->getContactsgridData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getContactsgridData());
            Mage::getSingleton("adminhtml/session")->setContactsgridData(null);
        } elseif (Mage::registry("contactsgrid_data")) {
            $form->setValues(Mage::registry("contactsgrid_data")->getData());
        }
        return parent::_prepareForm();
    }

}
