<?php

namespace App\Http\Controllers;

use App\Services\FileReportService;

class FileReportController extends Controller
{
    public function __construct(private FileReportService $fileReportService)
    {
        $this->middleware(['checkFilePublisher'])->only('getFileReport');
    }

    public function getFileReport($file_id)
    {
        $reports = $this->fileReportService->getFileReports($file_id);

        return $this->successResponse(
            $reports,
            'Reports fetched successfully',
        );
    }
}
