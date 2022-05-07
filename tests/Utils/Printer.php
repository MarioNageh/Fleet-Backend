<?php
/*
 * Created To Help Me To Debug The Testing Cases
 *
 */

namespace Tests\Utils;


use Symfony\Component\Console\Output\ConsoleOutput;

class Printer
{


    public static function printToConsole($s, $test = "")
    {
        $date = date('Y/m/d H:i:s');
        $out = new ConsoleOutput();
        $out->writeln("\033[0;33m [{$date}] [Test:{$test}] -> $s \033[0m");
    }
}
