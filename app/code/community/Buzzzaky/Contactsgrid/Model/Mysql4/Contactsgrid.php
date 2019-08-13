<?php
class Buzzzaky_Contactsgrid_Model_Mysql4_Contactsgrid extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("contactsgrid/contactsgrid", "id");
    }
}