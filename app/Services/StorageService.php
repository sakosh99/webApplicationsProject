<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

use App\Traits\FileUploader;

class StorageService
{
    use FileUploader;
    public function memoryUsage()
    {
        $memorySpace = Auth::user()->subscriptionPLan->max_memory_size_in_mega;

        $files = Auth::user()->files;
        /////////////////////////////////////



        $used = 0;
        foreach ($files as $file) {
            $used = $used + $this->getFileSize($file->file_path, 'M');
        }
        $usedPercent = (100 * $used) / $memorySpace;


        ////////////////////////////////////

        $free = $memorySpace - $used;
        $freePercent = (100 * $free) / $memorySpace;
        if ($memorySpace >= 1024) {
            $memorySpacesizeType = 'GB';
            $memorySpace = $memorySpace / 1024;
        } else {
            $memorySpacesizeType = 'MB';
        }

        if ($used >= 1024) {
            $usedSizeType = 'GB';
            $used = $used / 1024;
        } else {
            $usedSizeType = 'MB';
        }
        if ($free >= 1024) {
            $freeSizeType = 'GB';
            $free = $free / 1024;
        } else {
            $freeSizeType = 'MB';
        }

        return [
            'memorySpace' =>  $memorySpace . $memorySpacesizeType,
            'usedSpace' =>  number_format((float)$used, 2, '.', '') . $usedSizeType,
            'usedSpacePercent' => round($usedPercent, 1) . '%',
            'freeSpace' =>  number_format((float)$free, 2, '.', '') . $freeSizeType,
            'freeSpacePercent' => round($freePercent, 1) . '%',
        ];
    }
}
