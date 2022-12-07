<?php

namespace App\Repository;

use App\RepositoryInterface\FileReportRepositoryInterface;
use App\RepositoryInterface\FileRepositoryInterface;
use App\RepositoryInterface\GroupRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FileService
{
    use FileUploader;
    public function __construct(
        private FileRepositoryInterface $fileRepository,
        private GroupRepositoryInterface $groupRepository,
        private FileReportRepositoryInterface $fileReportRepository
    ) {
    }

    public function upload($validatedRequest)
    {
        $group = $this->groupRepository->find($validatedRequest['group_id']);
        DB::beginTransaction();
        request()->transaction = true;

        $path = $this->uploadFile(
            $validatedRequest['file'],
            $group->group_name . '/'
        );

        $file = $this->fileRepository->create([
            'file_name' => $validatedRequest['file']->getClientOriginalName(),
            'file_path' => $path,
            'status'    => 'free',
            'publisher_id' => Auth::user()->id,
            'group_id' => $group->id
        ]);

        $this->fileReportRepository->create($file->id, 'create', $file->created_at);

        DB::commit();

        return $file;
    }
    public function move($validatedRequest)
    {
        $file = $this->fileRepository->find($validatedRequest['file_id']);
        $group = $this->groupRepository->find($validatedRequest['group_id']);

        DB::beginTransaction();
        request()->transaction = true;

        $newPath = $this->moveFile(
            $file->file_path,
            $file->file_name,
            $group->group_name . '/'
        );

        $this->fileRepository->update(
            $file,
            [
                'file_path' => $newPath,
                'group_id'  => $group->id
            ]
        );

        $this->fileReportRepository->create($file->id, 'move', $file->updated_at, $group->id);

        DB::commit();
    }
    public function copy($validatedRequest)
    {
        $file = $this->fileRepository->find($validatedRequest['file_id']);
        $group = $this->groupRepository->find($validatedRequest['group_id']);

        DB::beginTransaction();
        request()->transaction = true;

        $newPath = $this->copyFile(
            $file->file_path,
            $file->file_name,
            $group->group_name . '/'
        );

        $newFile = $this->fileRepository->create([
            'file_name' => $file->file_name,
            'file_path' => $newPath,
            'status'    => 'free',
            'publisher_id' => Auth::user()->id,
            'group_id' => $group->id
        ]);

        $this->fileReportRepository->create($file->id, 'copy', $newFile->created_at, $group->id);

        DB::commit();
    }
    public function edit($validatedRequest)
    {
        $file = $this->fileRepository->find($validatedRequest['file_id']);

        DB::beginTransaction();
        request()->transaction = true;

        $this->deleteFile($file->file_path);

        $file_path = $this->uploadFile(
            $validatedRequest['file'],
            $file->group->group_name . '/'
        );

        $this->fileRepository->update($file, [
            'file_name' => $validatedRequest['file']->getClientOriginalName(),
            'file_path' => $file_path
        ]);

        $this->fileReportRepository->create($file->id, 'update', $file->updated_at);
        DB::commit();
    }
    public function rename($validatedRequest)
    {
        $file = $this->fileRepository->find($validatedRequest['file_id']);

        $oldName = $file->file_name;
        DB::beginTransaction();
        request()->transaction = true;

        $this->fileRepository->update($file, [
            'file_name' => $validatedRequest['file_name'] . '.' . $this->getFileExtension($file->file_path),
        ]);

        $this->fileReportRepository->create($file->id, 'rename', $file->updated_at, null, $oldName, $file->file_name);

        DB::commit();
    }
    public function reserve($validatedRequest)
    {
        $files = $this->fileRepository->findByMultiIDs($validatedRequest['file_id']);

        DB::beginTransaction();
        request()->transaction = true;

        foreach ($files as $file) {
            if ($file->status == 'reserved') {
                throw new Exception(
                    'Failed, One or more files are currently reserved, please try again later',
                    403
                );
            }

            $this->fileRepository->update($file, [
                'current_reserver_id' => Auth::user()->id,
                'status' => 'reserved'
            ]);

            $this->fileReportRepository->create($file->id, 'reserve', $file->updated_at);
        }

        $message = 'File reserved successfully';

        if (count($files) > 1) {
            $message = 'Files reserved successfully';
        }

        DB::commit();
        return $message;
    }
    public function cancelReserve($file_id)
    {
        $file = $this->fileRepository->find($file_id);

        DB::beginTransaction();
        request()->transaction = true;

        $this->fileRepository->update($file, [
            'current_reserver_id' => null,
            'status' => 'free'
        ]);

        $this->fileReportRepository->create($file->id, 'cancel_reserve', $file->updated_at);

        DB::commit();
    }
    public function delete($file_id)
    {
        $file = $this->fileRepository->find($file_id);
        DB::beginTransaction();
        request()->transaction = true;

        $this->deleteFile($file->file_path);
        $this->fileRepository->delete($file);

        DB::commit();
    }

    public function userFiles()
    {
        $files = $this->fileRepository->userFiles();
        return $files;
    }

    public function groupFiles($group_id)
    {
        $group = $this->groupRepository->find($group_id);

        $groupFiles = [];

        if (Cache::has($group->group_name)) {
            $groupFiles = Cache::get($group->group_name);
        } else {
            $groupFiles = $group->files;
            Cache::add($group->group_name, $groupFiles, 60);
        }

        return $groupFiles;
    }
}
