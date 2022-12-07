<?php

namespace App\Repository;

use App\Http\Resources\FileReportsResource;
use App\RepositoryInterface\FileReportRepositoryInterface;
use App\RepositoryInterface\FileRepositoryInterface;

class FileReportService
{
    public function __construct(
        private FileReportRepositoryInterface $fileReportRepository,
        private FileRepositoryInterface $fileRepository
    ) {
    }
    public function getFileReports($file_id)
    {
        $file = $this->fileRepository->find($file_id);

        return [
            'file_id' => $file_id,
            'file_name' => $file->file_name,
            'actions' => FileReportsResource::collection($this->fileReportRepository->getFileReports($file))
        ];
    }
}
