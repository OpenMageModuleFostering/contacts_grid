<?php

class Buzzzaky_Contactsgrid_Block_Adminhtml_Contactsgrid_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("contactsgridGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("contactsgrid/contactsgrid")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn("status", array(
            "header" => Mage::helper("contactsgrid")->__("Status"),
            "index" => "status",
            'type' => 'options',
            "width" => "100px",
            "options" => array(
                Buzzzaky_Contactsgrid_Model_Contactsgrid::STATUS_READ => 'Read',
                Buzzzaky_Contactsgrid_Model_Contactsgrid::STATUS_UNREAD => 'Unread',
                Buzzzaky_Contactsgrid_Model_Contactsgrid::STATUS_REPLIED => 'Replied'
            ),
             'frame_callback' => array($this, 'decorateStatus')
        ));
        $this->addColumn("id", array(
            "header" => Mage::helper("contactsgrid")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "id",
        ));

        $this->addColumn("name", array(
            "header" => Mage::helper("contactsgrid")->__("Name"),
            "index" => "name",
        ));
        $this->addColumn("email", array(
            "header" => Mage::helper("contactsgrid")->__("Email"),
            "index" => "email",
        ));
        $this->addColumn("telephone", array(
            "header" => Mage::helper("contactsgrid")->__("Telephone"),
            "index" => "telephone",
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }
    /**
     * Decorate status column values
     *
     * @return string
     */
    public function decorateStatus($value, $row, $column, $isExport)
    {
        if (!$row->getStatus()) {
            $cell = '<span class="grid-severity-critical"><span>'.$value.'</span></span>';
        } else {
            if ((int)$row->getStatus() === Buzzzaky_Contactsgrid_Model_Contactsgrid::STATUS_REPLIED) {
                $cell = '<span class="grid-severity-notice"><span>'.$value.'</span></span>';
            } else {
                $cell = '<span class="grid-severity-minor"><span>'.$value.'</span></span>';
            }
        }
        return $cell;
    }

    public function getRowUrl($row) {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_contactsgrid', array(
            'label' => Mage::helper('contactsgrid')->__('Remove Contactsgrid'),
            'url' => $this->getUrl('*/adminhtml_contactsgrid/massRemove'),
            'confirm' => Mage::helper('contactsgrid')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('mark_read_contacts', array(
            'label' => Mage::helper('contactsgrid')->__('Mark All Read'),
            'url' => $this->getUrl('*/adminhtml_contactsgrid/readAll'),
            'confirm' => Mage::helper('contactsgrid')->__('Are you sure?')
        ));
        return $this;
    }

}
