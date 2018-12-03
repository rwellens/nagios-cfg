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

    /**
     * remove blank line and comments
     *
     * @return \Generator|void
     */
    public function parse(): ?\Generator
    {
        $blockData = [];
        $nameKey = '';

        while ($line = fgets($this->file)) {
            $line = trim($line);

            if ($line == "" OR substr($line, 0, 1) == '#') {
                continue;
            }

            if ($line !== '}') {
                if (substr($line, 0, 6) == 'define') {
                    $type = trim(str_replace(['define', '.', '{'], '', $line));

                    $nameKey = $this->typesName[$type];
                    continue;
                }

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
            } else {
                yield [$blockName => $blockData];
                $blockName = '';
                $blockData = [];
                $nameKey = '';
            }
        }
    }

}