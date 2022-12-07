<?php

namespace App\Repository;

use App\Models\File;
use App\Models\FileReport;
use App\Models\User;
use App\RepositoryInterface\FileReportRepositoryInterface;
use App\Traits\ModelHelper;
use Illuminate\Support\Facades\Auth;

class DBFileReportsRepository implements FileReportRepositoryInterface
{
    use ModelHelper;
    public function getFileReports(File $file)
    {
        return $file->reports;
    }

    public function create($file_id, $action, $created_at, $to = null, $old_name = null, $new_name = null)
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

    public function find($FileReport_id)
    {
        $report = $this->findByIdOrFail(FileReport::class, 'FileReport', $FileReport_id);
        return $report;
    }
}
