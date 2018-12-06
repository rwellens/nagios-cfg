<?php
/**
 * CfgIteratorTest.php
 *
 * @date        17/11/2018
 * @file        DatIterator.php
 */

namespace NagiosDatTest;

use NagiosCfg\CfgIterator;
use PHPUnit\Framework\TestCase;

/**
 * Class DatIterator
 */
class CfgIteratorTest extends TestCase
{

    /**
     * @var CfgIterator
     */
    protected $iterator;

    protected function setUp()
    {
        $dataPath = realpath(__DIR__ . '/../_data');
        $this->iterator = new CfgIterator($dataPath . '/cfg/test_nagios2.cfg');
    }

    public function testLineIsCleaned()
    {
        foreach ($this->iterator->parse() as $blockType => $block) {

            $this->assertEquals('host', $blockType);
            $this->assertArrayHasKey('sandbox', $block);

            break;
        }
    }


    public function testIsUselessLine()
    {
        $this->assertTrue($this->iterator->isUselessLine('#lorem ipsum'));
        $this->assertTrue($this->iterator->isUselessLine(';lorem ipsum'));
        $this->assertfalse($this->iterator->isUselessLine('define command{'));
    }


}
