```
$converter = new \NagiosCfg\Converter();
$file = 'tests/_data/cfg/test_nagios2.cfg';

$array = $converter->cfgFileToArray($file);

$cfgString = $converter->arrayToCfgString($array);
```