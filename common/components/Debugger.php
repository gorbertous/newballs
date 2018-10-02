<?php

namespace common\components;

use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

/**
 * Class Debugger
 *
 * @package common\components
 */
class Debugger
{
    /**
     * Debugger constructor.
     */
    public function __construct()
    {
        $this->registerMethodAliases();
    }

    public function dd()
    {
        array_map(function ($value) {
            $this->dump($value);
        }, func_get_args());

        die(0);
    }

    /**
     * @param $value
     */
    public function dump($value)
    {
        if (class_exists(CliDumper::class)) {
            $dumper = (PHP_SAPI === 'cli') ? new CliDumper : new HtmlDumper;

            $dumper->dump((new VarCloner)->cloneVar($value));
        } else {
            var_dump($value);
        }
    }

    protected function registerMethodAliases()
    {
        require_once __DIR__ . '/debugger_helpers.php';
    }
}
