<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Migration\Code\Processor;

use Magento\Migration\Code\Processor\TableProcessor;

/**
 * Class TableProcessorTest
 */
class TableProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Migration\Code\Processor\TableProcessor
     */
    protected $model;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $classCbjectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $matcher;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $tokenHelper;

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * Setup the test
     */
    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $className = '\Magento\Framework\ObjectManagerInterface';
        $this->classCbjectManager = $this->getMock($className, [], [], '', false);

        $className = 'Magento\Migration\Code\Processor\Table\TableFunctionMatcher';
        $this->matcher = $this->getMock($className, [], [], '', false);

        $className = 'Magento\Migration\Code\Processor\TokenHelper';
        $this->tokenHelper = $this->getMock($className, [], [], '', false);


        $this->model = $this->objectManager->getObject(
            'Magento\Migration\Code\Processor\TableProcessor',
            [
                'objectManager' => $this->classCbjectManager,
                'matcher' => $this->matcher,
                'tokenHelper' => $this->tokenHelper,
            ]
        );
    }

    /**
     * test Process
     * @param mixed[] $tokens
     * @dataProvider testProcessProvider
     */
    public function testProcess($tokens)
    {
        $className = '\Magento\Migration\Code\Processor\Table\TableFunction\Table';
        $matched = $this->getMock($className, [], [], '', false);

        $matched->expects($this->atLeastOnce())
            ->method('convertToM2');

        $this->matcher->expects($this->atLeastOnce())
            ->method('match')
            ->willReturn($matched);

        $this->tokenHelper->expects($this->once())
            ->method('refresh')
            ->willReturn($tokens);

        $this->assertEquals($tokens, $this->model->process($tokens));
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testProcessProvider()
    {
        return [
            [
                'tokens' => [
                        [
                            0 => 379,
                            1 => ' ',
                            2 => 101,
                            3 => 'T_WHITESPACE',
                        ],
                        [
                            0 => 364,
                            1 => '=>',
                            2 => 101,
                            3 => 'T_DOUBLE_ARROW',
                        ],
                        [
                            0 => 379,
                            1 => ' ',
                            2 => 101,
                            3 => 'T_WHITESPACE',
                        ],
                        [
                            0 => 312,
                            1 => '$this',
                            2 => 101,
                            3 => 'T_VARIABLE',
                        ],
                        [
                            0 => 363,
                            1 => '->',
                            2 => 101,
                            3 => 'T_OBJECT_OPERATOR',
                        ],
                        [
                            0 => 310,
                            1 => 'getTable',
                            2 => 101,
                            3 => 'T_STRING',
                        ],
                        '(',
                        [
                            0 => 318,
                            1 => '\'catalogrule/rule_product\'',
                            2 => 101,
                            3 => 'T_CONSTANT_ENCAPSED_STRING',
                        ],
                        ')',
                        ')',
                        ')',
                ],
            ]
        ];
    }
}
