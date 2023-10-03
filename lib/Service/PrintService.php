<?php
namespace OCA\SkyPrint\Service;

use OC_Util;
use OC\Files\Filesystem;

use Symfony\Component\Process\Process;


class PrintService
{
    public function __construct()
    {
        OC_Util::setupFS();
    }

    public function print(string $printer, string $file, int $copies, string $orientation, string $media, string $range, int $nup)
    {
        $file = Filesystem::getLocalFile($file);

        $pageranges = $range ? "-o page-ranges=$range" : "";
        $command = "lp -d $printer $file -n $copies -o orientation-requested=$orientation -o media=$media $pageranges -o number-up=$nup";
        $process = new Process($command);
        $process->run();

        return array(
            'success' => $process->isSuccessful(),
            'error' => $process->getErrorOutput()
            // 'command' => $process->getCommandLine(), // Only for debugging
            // 'output' => $process->getOutput() // Only for debugging
        );
    }

    public function getPrinters()
    {
        $process = new Process("lpstat -p | awk '{print $2}'");
        $process->run();

        $success = $process->isSuccessful();
        $error = $process->getErrorOutput();
        $printers = array();

        if ($success) {
            $printers = array_map(
                function ($printer) {
                    return array(
                        'id' => $printer,
                        'name' => $printer
                    );
                },
                explode("\n", $process->getOutput())
            );
            array_pop($printers);
        }

        return array(
            'success' => $success,
            'error' => $error,
            'printers' => $printers
        );
    }
}