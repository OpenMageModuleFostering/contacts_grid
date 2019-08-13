<?php


class Buzzzaky_Contactsgrid_Block_Adminhtml_Contactsgrid extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_contactsgrid";
	$this->_blockGroup = "contactsgrid";
	$this->_headerText = Mage::helper("contactsgrid")->__("List Of All Contact Us Comments.");
	parent::__construct();
	$this->_removeButton('add');
	}

}