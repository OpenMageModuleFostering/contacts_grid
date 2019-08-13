<?php

class Buzzzaky_Contactsgrid_Model_Contactsgrid extends Mage_Core_Model_Abstract
{
    const STATUS_UNREAD = 0;
    const STATUS_READ = 1;
    const STATUS_REPLIED = 2;

    protected function _construct(){

       $this->_init("contactsgrid/contactsgrid");

    }
    
}
	 