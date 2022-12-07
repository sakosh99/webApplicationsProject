<?php

namespace App\RepositoryInterface;

use App\Models\File;

interface FileReportRepositoryInterface
{
    public function getFileReports(File $file);
    public function create($file_id, $action, $created_at, $to = null, $old_name = null, $new_name = null);
    public function find($FileReport_id);
}
