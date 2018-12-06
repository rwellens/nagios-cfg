<?php
/**
 * ConverterTest.php
 *
 * @date        17/11/2018
 * @file        DatIterator.php
 */

namespace NagiosDatTest;

use NagiosCfg\Converter;
use NagiosDat\DatIterator;
use NagiosDat\DatParser;
use NagiosDat\Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class DatIterator
 */
class ConverterTest extends TestCase
{

    public function testCfgFileToArray()
    {
        $dataPath = realpath(__DIR__ . '/../_data');

        $cfgConverter = new Converter();
        $data = $cfgConverter->cfgFileToArray($dataPath . '/cfg/services/utm.cfg');

        $normalData = [
            'service' => [
                'Check UTM-SUM' => [
                    'hostgroup_name'        => 'mdm',
                    'service_description'  => 'Check UTM-SUM',
                    'check_command'         => 'check-router-alive',
                    'use'                   => 'generic-service',
                    'notification_interval' => '0'
                ]
            ]
        ];

        $this->assertEquals($normalData, $data);

    }

    public function testArrayToCfgString()
    {
        $arrayData = [
            'service' => [
                'Check UTM-SUM' => [
                    'hostgroup_name'        => 'mdm',
                    'service_description'  => 'Check UTM-SUM',
                    'check_command'         => 'check-router-alive',
                    'use'                   => 'generic-service',
                    'notification_interval' => '0'
                ]
            ]
        ];

        $stringData =
'define service{
    hostgroup_name  mdm
    service_description  Check UTM-SUM
    check_command  check-router-alive
    use  generic-service
    notification_interval  0
}';


        $cfgConverter = new Converter();
        $cfgString = $cfgConverter->arrayToCfgString($arrayData);


        $this->assertSame($stringData, $cfgString);


    }




}
