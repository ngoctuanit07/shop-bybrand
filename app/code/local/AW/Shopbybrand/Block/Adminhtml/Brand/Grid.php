<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Shopbybrand
 * @version    1.3.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Shopbybrand_Block_Adminhtml_Brand_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('brandsGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var AW_Shopbybrand_Model_Resource_Brand_Collection $collection */
        $collection = Mage::getModel('awshopbybrand/brand')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            array(
                'header' => $this->__('ID'),
                'align'  => 'right',
                'width'  => '5',
                'index'  => 'id',
            )
        );

        $this->addColumn(
            'title',
            array(
                'header' => $this->__('Title'),
                'align'  => 'left',
                'index'  => 'title',
            )
        );

        $this->addColumn(
            'icon',
            array(
                'header'   => $this->__('Icon'),
                'width'    => AW_Shopbybrand_Model_Brand::ICON_WIDTH,
                'index'    => 'icon',
                'type'     => 'image',
                'align'    => 'center',
                'escape'   => true,
                'sortable' => false,
                'filter'   => false,
                'renderer' => 'AW_Shopbybrand_Block_Adminhtml_Renderer_Image',
            )
        );

        $this->addColumn(
            'priority',
            array(
                'header' => $this->__('Sort Order'),
                'align'  => 'right',
                'width'  => '5',
                'index'  => 'priority',
            )
        );

        $this->addColumn(
            'brand_status',
            array(
                'header'  => $this->__('Status'),
                'align'   => 'center',
                'width'   => '80px',
                'index'   => 'brand_status',
                'type'    => 'options',
                'options' => Mage::getModel('awshopbybrand/source_status')->toOptions(),
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'    => $this->__('Action'),
                'align'     => 'center',
                'width'     => '48px',
                'type'      => 'action',
                'actions'   => array(
                    array(
                        'caption' => $this->__('Edit'),
                        'url'     => array('base' => '*/*/edit'),
                        'field'   => 'id',
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
                'index'     => 'id',
            )
        );

        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportXml', $this->__('XML'));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'   => $this->__('Delete'),
                'url'     => $this->getUrl('*/*/massDelete'),
                'confirm' => $this->__('Are you sure?'),
            )
        );

        $this->getMassactionBlock()->addItem(
            'brand_status', array(
                'label'      => $this->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name'   => 'brand_status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => $this->__('Status'),
                        'values' => Mage::getModel('awshopbybrand/source_status')->toOptionArray(),
                    )
                ),
                'confirm'    => $this->__('Are you sure?'),
            )
        );
        return $this;
    }
}