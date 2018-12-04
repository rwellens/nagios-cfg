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

    /**
     * @param $file
     *
     * @return array
     */
    public function getFileData($file): array
    {
        $data = [];
        $iterator = new CfgIterator($file);
        foreach ($iterator->parse() as $blockType => $block) {
            foreach ($block as $blockName => $blockData) {
                $data[$blockType][$blockName] = $blockData;
            }
        }

        return $data;
    }


    /**
     * @param $data
     *
     * @return string
     */
    public function convertToDat($data): string
    {
        $space = '    ';
        $str = '';

        foreach ($data as $blockType => $block) {
            foreach ($block as $blockName => $blockData) {
                $str .= 'define ' . $blockType . "{" . PHP_EOL;
                foreach ($blockData as $key => $value) {
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $str .= $space . $key . '  ' . $value . PHP_EOL;
                }
                $str .= '}' . PHP_EOL;
                $str .= "
";
            }
        }


        return trim($str);
    }


}