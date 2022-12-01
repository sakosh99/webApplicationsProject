<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUploader
{
    public function uploadFile($file, $repository): string
    {

        // $fileName = $this->fileName($file);

        // $realPath = $repository . $fileName;

        // Storage::disk('public')->put($realPath, File::get($file));

        // $filePath   = 'storage/' . $realPath;

        // return $filePath;

        $fileName = $this->fileName($file);

        $file->move('storage/' . $repository, $fileName);

        $realPath = 'storage/' . $repository . $fileName;

        return $realPath;
    }

    protected function fileName($file): string
    {
        return  Carbon::now()->format('Y_m_d_u') . '_' . $file->getClientOriginalName();
    }

    protected function deleteFile($fileName): bool
    {
        if (file_exists(public_path($fileName))) {
            unlink(public_path($fileName));
            return true;
        }
        return false;
    }

    protected function moveFile($oldFilePath, $oldFileName, $newPath): string
    {
        // $file = File::get(public_path($oldFilePath));

        // $fileName = Carbon::now()->format('Y_m_d_u') . '_' . $oldFileName;

        // $realPath = $newPath . $fileName;

        // Storage::disk('public')->put($realPath, $file);

        // unlink(public_path($oldFilePath));

        // $filePath   = 'storage/' . $realPath;

        // return $filePath;

        $realNewPath = 'storage/' . $newPath;

        if (!File::exists(public_path($realNewPath))) {
            File::makeDirectory(public_path($realNewPath), 0775, true);
        }
        
        $fileName = Carbon::now()->format('Y_m_d_u') . '_' . $oldFileName;

        $fullPath = $realNewPath . $fileName;

        File::move(public_path($oldFilePath), public_path($fullPath));

        return $fullPath;
    }

    protected function copyFile($oldFilePath, $oldFileName, $newPath): string
    {
        // $file = File::get(public_path($oldFilePath));

        // $fileName = Carbon::now()->format('Y_m_d_u') . '_' . $oldFileName;

        // $realPath = $newPath . $fileName;

        // Storage::disk('public')->put($realPath, $file);

        // $filePath   = 'storage/' . $realPath;

        // return $filePath;


        $realNewPath = 'storage/' . $newPath;

        if (!File::exists(public_path($realNewPath))) {
            File::makeDirectory(public_path($realNewPath), 0775, true);
        }
        $fileName = Carbon::now()->format('Y_m_d_u') . '_' . $oldFileName;

        $fullPath = $realNewPath . $fileName;

        File::copy(public_path($oldFilePath), public_path($fullPath));

        return $fullPath;
    }

    protected function getFileExtension($FilePath): string
    {
        $infoPath = pathinfo(public_path($FilePath));

        return $infoPath['extension'];
    }
}
