<?php
namespace OCA\CloudPrint\Controller;

use OCA\CloudPrint\Service\PrintService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

class CloudprintController extends Controller
{

    public function __construct($appName, IRequest $request, PrintService $printService)
    {
        parent::__construct($appName, $request);
        $this->printService = $printService;
    }

    public function printfile(string $printer, string $file, int $copies, string $orientation, string $media, string $range, int $nup)
    {
        return new JSONResponse(
            $this->printService->print($printer, $file, $copies, $orientation, $media, $range, $nup)
        );
    }

    public function printers()
    {
        return new JSONResponse(
            $this->printService->getPrinters()
        );
    }
}