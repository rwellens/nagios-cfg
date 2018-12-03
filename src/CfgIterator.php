<?php
/**
 * CfgIterator.php
 *
 * @date        2018-12-03
 * @file        CfgIterator.php
 */

namespace NagiosCfg;

/**
 * CfgIterator
 */
class CfgIterator
{

    /**
     * @var array
     */
    protected $typesName = [
        'service'      => 'service_description',
        'host'         => 'host_name',
        'timeperiod'   => 'timeperiod_name',
        'contactgroup' => 'contactgroup_name',
        'contact'      => 'contact_name',
        'hostgroup'    => 'hostgroup_name',
    ];

    /**
     * @var resource
     */
    protected $file;

    /**
     * DatIterator constructor.
     *
     * @param $file
     */
    public function __construct(string $file)
    {
        $this->file = fopen($file, 'r');
    }

    /***
     * @return \Generator|void
     */
    public function parse(): ?\Generator
    {
        $blockData = [];
        $nameKey = '';
        $blockName = '';

        while ($line = fgets($this->file)) {
            $line = trim($line);

            if ($this->isUselessLine($line)) {
                continue;
            }

            if ($line !== '}') {
                if (substr($line, 0, 6) == 'define') {
                    $type = trim(str_replace(['define', '.', '{'], '', $line));

                    $nameKey = $this->typesName[$type];
                    continue;
                }

                $this->blockData($line, $nameKey, $blockName, $blockData);

            } else {
                yield [$blockName => $blockData];
                $blockName = '';
                $blockData = [];
                $nameKey = '';
            }
        }
    }

    /**
     * @param string $line
     * @param string $nameKey
     * @param string $blockName
     * @param array  $blockData
     */
    protected function blockData(string $line, string $nameKey, string &$blockName, array &$blockData)
    {
        preg_match("/(\w*)\s*([\w|!|:|,|-| |.|-]*)/", $line, $matched);

        if (strpos($line, $nameKey) !== false) {
            $blockName = trim($matched[2]);
        } else {
            $exploded = explode(',', $matched[2]);
            if (count($exploded) > 1) {
                array_map('trim', $exploded);
                $blockData[$matched[1]] = $exploded;
            } else {
                $blockData[$matched[1]] = trim($matched[2]);
            }
        }
    }

    /**
     * @param string $line
     *
     * @return bool
     */
    protected function isUselessLine(string $line): bool
    {
        return $line == "" OR substr($line, 0, 1) == '#';
    }

}