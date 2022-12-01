<?php

namespace App\Http\Controllers;

use App\Http\Requests\files\CopyFileRequest;
use App\Http\Requests\files\EditFileRequest;
use App\Http\Requests\files\MoveFileRequest;
use App\Http\Requests\files\RenameFileRequest;
use App\Http\Requests\files\UploadFileRequest;

use App\Http\Requests\ReserveFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\Group;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['userHasPermissionOnGroup', 'fileNameConflict'])->only('upload');

        $this->middleware([
            'userHasPermissionOnGroup', 'checkFilePublisher',
            'checkFileStatus', 'fileNameConflict',
            'checkIfUserReservedFileBeforeAction'
        ])->only('moveFileToNewGroup');

        $this->middleware([
            'userHasPermissionOnGroup',
            'checkFileStatus', 'fileNameConflict',
            'checkIfUserReservedFileBeforeAction'
        ])->only('copyFileToNewGroup');

        $this->middleware(['checkFileStatus', 'checkIfFileNameChanged', 'checkIfUserReservedFileBeforeAction'])->only('editFile');

        $this->middleware(['checkFileStatus', 'checkFilePublisher', 'checkIfUserReservedFileBeforeAction'])->only('renameFile');

        $this->middleware(['checkFilesStatus'])->only('reserveFiles');

        $this->middleware(['checkFileStatus', 'checkIfUserReservedFileBeforeAction'])->only('cancelReserveFile');

        $this->middleware(['checkFilePublisher', 'checkFileStatus', 'checkIfUserReservedFileBeforeAction'])->only('delete');

        $this->middleware(['userHasPermissionOnGroup'])->only('getGroupFiles');
    }

    public function upload(UploadFileRequest $request)
    {
        $group = $this->findByIdOrFail(Group::class, 'Group', request()->group_id);
        DB::beginTransaction();
        request()->transaction = true;

        $path = $this->uploadFile(
            $request->file,
            $group->group_name . '/'
        );

        $file = File::create([
            'file_name' => $request->file->getClientOriginalName(),
            'file_path' => $path,
            'status'    => 'free',
            'publisher_id' => Auth::user()->id,
            'group_id' => $group->id
        ]);

        FileReportController::createReport($file->id, 'create', $file->created_at);

        DB::commit();

        return $this->successResponse(
            null,
            'File uploaded successfully',
        );
    }

    public function moveFileToNewGroup(MoveFileRequest $request)
    {
        $file = $this->findByIdOrFail(File::class, 'File', $request->file_id);
        $group = $this->findByIdOrFail(Group::class, 'Group', $request->group_id);

        DB::beginTransaction();
        request()->transaction = true;

        $newPath = $this->moveFile(
            $file->file_path,
            $file->file_name,
            $group->group_name . '/'
        );

        $file->update([
            'file_path' => $newPath,
            'group_id'  => $group->id
        ]);

        FileReportController::createReport($file->id, 'move', $file->updated_at, $group->id);

        DB::commit();

        return $this->successResponse(
            null,
            'File moved successfully'
        );
    }

    public function copyFileToNewGroup(CopyFileRequest $request)
    {
        $file = $this->findByIdOrFail(File::class, 'File', $request->file_id);
        $group = $this->findByIdOrFail(Group::class, 'Group', $request->group_id);

        DB::beginTransaction();
        request()->transaction = true;

        $newPath = $this->copyFile(
            $file->file_path,
            $file->file_name,
            $group->group_name . '/'
        );

        $newFile = File::create([
            'file_name' => $file->file_name,
            'file_path' => $newPath,
            'status'    => 'free',
            'publisher_id' => Auth::user()->id,
            'group_id' => $group->id
        ]);

        FileReportController::createReport($file->id, 'copy', $newFile->created_at, $group->id);

        DB::commit();

        return $this->successResponse(
            null,
            'File copied successfully'
        );
    }

    public function editFile(EditFileRequest $request)
    {
        $file = $this->findByIdOrFail(File::class, 'File', $request->file_id);

        DB::beginTransaction();
        request()->transaction = true;

        $this->deleteFile($file->file_path);

        $file_path = $this->uploadFile(
            $request->file,
            $file->group->group_name . '/'
        );

        $file->update([
            'file_name' => $request->file->getClientOriginalName(),
            'file_path' => $file_path
        ]);

        FileReportController::createReport($file->id, 'update', $file->updated_at);
        DB::commit();

        return $this->successResponse(
            null,
            'File updated successfully',
        );
    }

    public function renameFile(RenameFileRequest $request)
    {
        $file = $this->findByIdOrFail(File::class, 'File', $request->file_id);

        $oldName = $file->file_name;
        DB::beginTransaction();
        request()->transaction = true;

        $file->update([
            'file_name' => $request->file_name . '.' . $this->getFileExtension($file->file_path),
        ]);

        FileReportController::createReport($file->id, 'rename', $file->updated_at, null, $oldName, $file->file_name);

        DB::commit();

        return $this->successResponse(
            null,
            'File renamed successfully',
        );
    }

    public function reserveFiles(ReserveFileRequest $request)
    {
        $files = File::whereIn('id', $request->file_id)->get();

        DB::beginTransaction();
        request()->transaction = true;


        foreach ($files as $file) {
            if ($file->status == 'reserved') {
                throw new Exception(
                    'Failed, One or more files are currently reserved, please try again later',
                    403
                );
            }
            $file->update([
                'current_reserver_id' => Auth::user()->id,
                'status' => 'reserved'
            ]);
            FileReportController::createReport($file->id, 'reserve', $file->updated_at);
        }

        $message = 'File reserved successfully';

        if (count($files) > 1) {
            $message = 'Files reserved successfully';
        }

        DB::commit();
        return $this->successResponse(
            null,
            $message,
        );
    }

    public function cancelReserveFile($file_id)
    {

        $file = $this->findByIdOrFail(File::class, 'File', $file_id);

        DB::beginTransaction();
        request()->transaction = true;

        $file->update([
            'current_reserver_id' => null,
            'status' => 'free'
        ]);

        FileReportController::createReport($file->id, 'cancel_reserve', $file->updated_at);

        DB::commit();

        return $this->successResponse(
            null,
            'File reservation cancelled successfully',
        );
    }

    public function delete($file_id)
    {
        $file = $this->findByIdOrFail(File::class, 'File', $file_id);
        DB::beginTransaction();
        request()->transaction = true;

        $this->deleteFile($file->file_path);
        $file->delete();

        DB::commit();
        return $this->successResponse(
            null,
            'File deleted successfully',
        );
    }


    //////////////////////////////////////////////get functions///////////////////////////////////////////

    public function getAllUserFiles()
    {
        $files = File::where('publisher_id', Auth::user()->id)->get();

        return $this->successResponse(
            FileResource::collection($files),
            'Files fetched successfully',
        );
    }

    public function getGroupFiles($group_id)
    {
        $files = Group::where('id', $group_id)->first()->files;

        return $this->successResponse(
            FileResource::collection($files),
            'Files fetched successfully',
        );
    }
}
