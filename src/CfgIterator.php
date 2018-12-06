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
    public static $typesName = [
        'service'      => 'service_description',
        'host'         => 'host_name',
        'timeperiod'   => 'timeperiod_name',
        'contactgroup' => 'contactgroup_name',
        'contact'      => 'contact_name',
        'hostgroup'    => 'hostgroup_name',
        'command'      => 'command_name',
        'servicegroup' => 'servicegroup_name',
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
        $type = '';

        while ($line = fgets($this->file)) {
            $line = trim($line);

            if ($this->isUselessLine($line)) {
                continue;
            }

            if ($line !== '}') {
                if (substr($line, 0, 6) == 'define') {
                    $type = trim(str_replace(['define', '.', '{'], '', $line));

                    $nameKey = self::$typesName[$type];
                    continue;
                }

                $this->blockData($line, $nameKey, $blockName, $blockData);
            } else {
                yield $type => [$blockName => $blockData];
                $blockName = $nameKey = $type = '';
                $blockData = [];
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
        preg_match("/([\w|\-]*)\s*([\w|!|:|,|\-| |.|$|\/]*)/", $line, $matched);

        $exploded = explode(',', $matched[2]);
        if (count($exploded) > 1) {
            $blockData[$matched[1]] = array_map('trim', $exploded);
        } else {
            if (strpos($line, $nameKey) !== false) {
                $blockName = trim($matched[2]);
            }

            $blockData[$matched[1]] = trim($matched[2]);
        }
    }

    /**
     * @param string $line
     *
     * @return bool
     */
    public function isUselessLine(string $line): bool
    {
        $firstChar = substr($line, 0, 1);

        return $line == "" or $firstChar == '#' or $firstChar == ';';
    }
}
