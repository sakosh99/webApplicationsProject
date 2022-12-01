<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileReportsResource;
use App\Models\File;
use App\Models\FileReport;
use Illuminate\Support\Facades\Auth;

class FileReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['checkFilePublisher'])->only('getFileReport');
    }

    public function getFileReport($file_id)
    {
        $file = $this->findByIdOrFail(File::class, 'File', $file_id);

        $reports = $file->reports;

        return $this->successResponse(
            [
                'file_id' => $file->id,
                'file_name' => $file->file_name,
                'actions' => FileReportsResource::collection($reports)
            ],
            'Reports fetched successfully',
        );
    }

    public static function createReport($file_id, $action, $created_at, $to = null, $old_name = null, $new_name = null)
    {
        FileReport::create([
            'file_id' => $file_id,
            'user_id' => Auth::user()->id,
            'action' => $action,
            'to_group' => $to,
            'old_file_name' => $old_name,
            'new_file_name' => $new_name,
            'created_at' => $created_at
        ]);
    }
}
