<?php
/**
 * CfgParsor.php
 *
 * @date        2018-12-03
 * @file        CfgParsor.php
 */

namespace NagiosCfg;

/**
 * CfgParsor
 */
class CfgParsor
{


    public function getService($file)
    {

        $path = realpath($file);

        $iterator = new CfgIterator($path);

        foreach($iterator->parse() as $v){

            var_dump($v);

        }

        die;


    }




}