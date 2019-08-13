<?php
class Buzzzaky_Contactsgrid_Block_Adminhtml_Contactsgrid_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
       /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
		public function __construct()
		{
				parent::__construct();
				$this->setId("contactsgrid_tabs");
				$this->setDestElementId("edit_form");
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("contactsgrid")->__("Contact Us information"),
				"title" => Mage::helper("contactsgrid")->__("Contact Us information"),
				"content" => $this->getLayout()->createBlock("contactsgrid/adminhtml_contactsgrid_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
