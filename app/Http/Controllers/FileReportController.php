<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileReportsResource;
use App\Models\File;
use App\Models\FileReport;
use App\Repository\FileReportService;
use Illuminate\Support\Facades\Auth;

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
