<?php

namespace App\Http\Controllers;

use App\Http\Requests\files\CopyFileRequest;
use App\Http\Requests\files\EditFileRequest;
use App\Http\Requests\files\MoveFileRequest;
use App\Http\Requests\files\RenameFileRequest;
use App\Http\Requests\files\UploadFileRequest;

use App\Http\Requests\ReserveFileRequest;
use App\Http\Resources\FileResource;
use App\Services\FileService;

class FileController extends Controller
{
    public function __construct(private FileService $fileService)
    {
        $this->middleware(['userHasPermissionOnGroup', 'fileNameConflict', 'checkMemoryUsage'])->only('upload');

        $this->middleware([
            'userHasPermissionOnGroup', 'checkFilePublisher',
            'checkFileStatus', 'fileNameConflict',
            'checkIfUserReservedFileBeforeAction'
        ])->only('moveFileToNewGroup');

        $this->middleware([
            'userHasPermissionOnGroup',
            'checkMemoryUsage',
            'checkFileStatus', 'fileNameConflict',
            'checkIfUserReservedFileBeforeAction'
        ])->only('copyFileToNewGroup');

        $this->middleware(['checkFileStatus', 'checkIfFileNameChanged', 'checkIfUserReservedFileBeforeAction'])->only('editFile');

        $this->middleware(['checkFileStatus', 'checkFilePublisher', 'checkIfUserReservedFileBeforeAction', 'fileNameConflict'])->only('renameFile');

        $this->middleware(['checkFilesStatus'])->only('reserveFiles');

        $this->middleware(['checkFileStatus', 'checkIfUserReservedFileBeforeAction'])->only('cancelReserveFile');

        $this->middleware(['checkFilePublisher', 'checkFileStatus', 'checkIfUserReservedFileBeforeAction'])->only('delete');

        $this->middleware(['userHasPermissionOnGroup'])->only('getGroupFiles');
    }

    public function upload(UploadFileRequest $request)
    {
        $this->fileService->upload($request);

        return $this->successResponse(
            null,
            'File uploaded successfully',
        );
    }

    public function moveFileToNewGroup(MoveFileRequest $request)
    {
        $this->fileService->move($request);

        return $this->successResponse(
            null,
            'File moved successfully'
        );
    }

    public function copyFileToNewGroup(CopyFileRequest $request)
    {
        $this->fileService->copy($request);

        return $this->successResponse(
            null,
            'File copied successfully'
        );
    }

    public function editFile(EditFileRequest $request)
    {
        $this->fileService->edit($request);

        return $this->successResponse(
            null,
            'File updated successfully',
        );
    }

    public function renameFile(RenameFileRequest $request)
    {
        $this->fileService->rename($request);

        return $this->successResponse(
            null,
            'File renamed successfully',
        );
    }

    public function reserveFiles(ReserveFileRequest $request)
    {
        $message = $this->fileService->reserve($request);

        return $this->successResponse(
            null,
            $message,
        );
    }

    public function cancelReserveFile($file_id)
    {
        $this->fileService->cancelReserve($file_id);

        return $this->successResponse(
            null,
            'File reservation cancelled successfully',
        );
    }

    public function delete($file_id)
    {
        $this->fileService->delete($file_id);

        return $this->successResponse(
            null,
            'File deleted successfully',
        );
    }


    //////////////////////////////////////////////get functions///////////////////////////////////////////

    public function getAllUserFiles()
    {
        $files = $this->fileService->userFiles();

        return $this->successResponse(
            FileResource::collection($files),
            'Files fetched successfully',
        );
    }

    public function getGroupFiles($group_id)
    {
        $files = $this->fileService->groupFiles($group_id);

        return $this->successResponse(
            FileResource::collection($files),
            'Files fetched successfully',
        );
    }
}
