<?php
declare(strict_types=1);

namespace OCA\SkyPrint\Controller;

use OCA\SkyPrint\Service\PrintService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

class SkyprintController extends Controller
{

    public function __construct($appName, IRequest $request, PrintService $printService)
    {
        parent::__construct($appName, $request);
        $this->printService = $printService;
    }

	/**
	 * @NoAdminRequired
	 */
    public function printfile(string $printer, string $file, int $copies, string $orientation, string $media, string $range, int $nup)
    {
        return new JSONResponse(
            $this->printService->print($printer, $file, $copies, $orientation, $media, $range, $nup)
        );
    }

	/**
	 * @NoAdminRequired
	 */
    public function printers()
    {
        return new JSONResponse(
            $this->printService->getPrinters()
        );
    }
}