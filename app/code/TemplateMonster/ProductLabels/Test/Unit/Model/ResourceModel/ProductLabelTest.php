<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Test\Unit\Model\ResourceModel;

class ProductLabelTest extends \PHPUnit_Framework_TestCase
{

    protected $_objectManager;
    protected $_resourceModel;

    public function setUp()
    {
        $this->_objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_resourceModel =
            $this->_objectManager->getObject('TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel');
    }

    /**
     * @test
     * @dataProvider fieldWithArrayToStringData
     */
    public function _fieldWithArrayToString($data, $customerResult, $websiteResult)
    {
        $dataObject = $this->_objectManager->getObject("Magento\Framework\DataObject");
        $dataObject->setData($data);

        $resource = $this->_resourceModel;
        $ref = new \ReflectionMethod(get_class($resource), '_fieldWithArrayToString');
        $ref->setAccessible(true);
        $ref->invokeArgs($resource, [$dataObject]);

        $this->assertEquals($customerResult, $dataObject->getCustomerGroupIds());
        $this->assertEquals($websiteResult, $dataObject->getWebsiteIds());
    }


    public function fieldWithArrayToStringData()
    {
        return [
            'Values in string format'=>[
                [
                    'customer_group_ids'=>'1 2 3',
                    'website_ids' => '1,2,3,4,5,6'
                ],
                    '1 2 3',
                    '1,2,3,4,5,6'
            ],

            'Values in array format'=>[
                [
                    'customer_group_ids'=>[1, 2, 3],
                    'website_ids' => [1,2,3,4,5,6]
                ],
                    '1,2,3',
                    '1,2,3,4,5,6'
            ],

            'Values in null or does not exists'=>[
                [
                ],
                null,
                null
            ],
        ];
    }
}
