<?php
/**
 * Converter.php
 *
 * @date        2018-12-03
 * @file        Converter.php
 */

namespace NagiosCfg;

/**
 * Converter
 */
class Converter
{

    /**
     * @param string $filePath
     *
     * @return array
     */
    public function cfgFileToArray($filePath): array
    {
        $data = [];
        $iterator = new CfgIterator($filePath);
        foreach ($iterator->parse() as $blockType => $block) {
            foreach ($block as $blockName => $blockData) {
                $data[$blockType][$blockName] = $blockData;
            }
        }

        return $data;
    }


    /**
     * @param array $data
     *
     * @return string
     */
    public function arrayToCfgString(array $data): string
    {
        $str = '';

        foreach ($data as $blockType => $block) {
            foreach ($block as $blockData) {
                $str .= 'define ' . $blockType . "{" . PHP_EOL;
                foreach ($blockData as $key => $value) {
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $str .= '    ' . $key . '  ' . $value . PHP_EOL;
                }
                $str .= '}' . PHP_EOL;
                $str .= "
";
            }
        }


        return trim($str);
    }
}
