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

    public function printfile(string $printer, bool $preview, string $file, string $orientation, int $copies)
    {
        return new JSONResponse(
            $this->printService->print($printer, $file, $orientation, $copies)
        );
    }

    public function printers()
    {
        return new JSONResponse(
            $this->printService->getPrinters()
        );
    }
}