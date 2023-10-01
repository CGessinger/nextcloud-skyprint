<?php
namespace OCA\CloudPrint\Service;

use OC_Util;
use OC\Files\Filesystem;

use Symfony\Component\Process\Process;


class PrintService
{
    public function __construct()
    {
        OC_Util::setupFS();
    }

    public function print(string $printer, string $file, string $orientation, int $copies)
    {
        $file = Filesystem::getLocalFile($file);

        $command = "lp -d $printer $file -o orientation-requested=$orientation -n $copies";
        $process = new Process($command);
        $process->run();

        $success = $process->isSuccessful();
        $error = $process->getErrorOutput();

        return array(
            'success' => $success,
            'error' => $error,
            'command' => $command,
            'output' => $process->getOutput()
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