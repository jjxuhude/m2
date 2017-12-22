<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */
namespace TemplateMonster\ProductLabels\Test\Unit\Controller\Adminhtml\Productlabel;

class SaveTest extends \PHPUnit_Framework_TestCase
{

    protected $_objectManager;
    protected $_saveAction;

    public function setUp()
    {
        $this->_objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_saveAction =
            $this->_objectManager->getObject('TemplateMonster\ProductLabels\Controller\Adminhtml\Productlabel\Save');
    }

    /**
     * @test
     * @dataProvider dataCheckIfFieldExists
     */
    public function _checkIfFieldExists($data, $field, $valueName, $resultExpect)
    {
        $save = $this->_saveAction;
        $ref = new \ReflectionMethod(get_class($save), '_checkIfFieldExists');
        $ref->setAccessible(true);
        $result = $ref->invokeArgs($save, [$data, $field, $valueName]);
        $this->assertEquals($resultExpect, $result);
    }

    /**
     * @test
     * @dataProvider dataPrepareFieldImg
     */
    public function _prepareFieldImg($data, $dataOrigin, $resultExpect)
    {
        $save = $this->_saveAction;
        $ref = new \ReflectionMethod(get_class($save), '_prepareFieldImg');
        $ref->setAccessible(true);
        $result = $ref->invokeArgs($save, [$data, $dataOrigin, '/']);
        foreach ($resultExpect as $k=>$v) {
            $this->assertEquals($v, $result[$k]);
        }
    }

    public function dataPrepareFieldImg()
    {
        $data = [
            'product_image_label'=>'img2.png',
            'product_text_background'=>['value'=>'img2.png']
        ];
        $dataOrigin = [
            'product_image_label'=>['value'=>'img1.png','delete'=>1],
            'product_text_background'=>['value'=>'img2.png']
        ];
        $result = ['product_image_label'=>'','product_text_background'=>'img2.png'];

        $data2 = [
            'product_image_label'=>'img1.png',
            'product_text_background'=>'img2.png'
        ];
        $dataOrigin2 = [
            'product_image_label'=>'img1.png',
            'product_text_background'=>'img2.png'
        ];
        $result2 = [
            'product_image_label'=>'img1.png',
            'product_text_background'=>'img2.png'
        ];

        $data3 = [
            'product_image_label'=>'img1.png',
            'product_text_background'=>'img2.png'
        ];
        $dataOrigin3 = [
            'product_image_label'=>'img1.png',
            'product_text_background'=>'img2.png'
        ];
        $result3 = [
            'product_image_label'=>'img1.png',
            'product_text_background'=>'img2.png'
        ];

        return [
            ['first' => $data,$dataOrigin,$result],
            ['second' => $data2,$dataOrigin2,$result2],
            ['third' => $data3,$dataOrigin3,$result3]
        ];
    }



    public function dataCheckIfFieldExists()
    {
        return [
            'product_text_background return img2.png' => [
                [
                    'product_image_label'=>['value'=>'img1.png','delete'=>1],
                    'product_text_background'=>['value'=>'img2.png']
                ],
                'product_text_background',
                'value',
                'img2.png'
            ],
            'product_image_label return one' => [
                [
                    'product_image_label'=>['value'=>'img1.png','delete'=>1],
                    'product_text_background'=>['value'=>'img2.png']
                ],
                'product_image_label',
                'delete',
                1
            ],
            'product_image_label return false data is not array' => [
                'product_image_label'=>['value'=>'img1.png','delete'=>1]
                ,
                'product_image_label',
                'delete',
                false
            ],
            'product_text_background key delete does not exist on data' => [
                [
                    'product_image_label'=>['value'=>'img1.png','delete'=>1],
                    'product_text_background'=>['value'=>'img2.png']
                ],
                'product_text_background',
                'delete',
                false
            ],
            'product_text_background key delete does not exist on data' => [
                [
                    'product_text_background'=>'value'
                ],
                'product_text_background',
                'delete',
                false
            ],
        ];
    }
}
