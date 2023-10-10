<?php
namespace OCA\SkyPrint\Service;

use OC_Util;
use OC\Files\Filesystem;
use Psr\Log\LoggerInterface;

use Symfony\Component\Process\Process;


class PrintService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->testsDir = realpath(__DIR__ . "/../../ipptool");
        $this->localipp = "ipp://localhost/printers";
        OC_Util::setupFS();
    }

    public function print(string $printer, string $file, int $copies, string $orientation, string $media, string $range, int $nup)
    {
        $file = Filesystem::getLocalFile($file);

        $pageranges = $range ? "-o page-ranges=$range" : "";
        $command = "lp -d $printer $file -n $copies -o orientation-requested=$orientation -o media=$media $pageranges -o number-up=$nup";
        $process = new Process($command);
        $process->run();

        $success = $process->isSuccessful();
        $message = "Printing successful!";

        if (!$success) {
            $error = $process->getErrorOutput();
            $logMessage = "Printing failed: $command with output: $error";
            $this->logger->error($logMessage, ['skyprint' => 'skyprint printing error']);
            $message = "Printing failed! Check logs for more information.";
        }

        return array(
            'success' => $success,
            'message' => $message
            // 'command' => $process->getCommandLine(), // Only for debugging
            // 'output' => $process->getOutput() // Only for debugging
        );
    }

    public function getPrinters()
    {
        $testfile = realpath($this->testsDir . '/get-printers.test');
        $process = new Process(['ipptool', '-c', $this->localipp, $testfile]);
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
            array_shift($printers);
            array_pop($printers);
        }

        return array(
            'success' => $success,
            'error' => $error,
            'printers' => $printers
        );
    }
}