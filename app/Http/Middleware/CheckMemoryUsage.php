<?php

namespace App\Http\Middleware;

use App\Models\File;
use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\FileUploader;
use App\Traits\ModelHelper;
use Exception;

class CheckMemoryUsage
{
    use FileUploader, ModelHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $planMemory = Auth::user()->subscriptionPLan->max_memory_size_in_mega;
        $files = Auth::user()->files;

        $memoryUsage = 0;
        $requestedFileSize = 0;
        foreach ($files as $file) {
            $memoryUsage = $memoryUsage + $this->getFileSize($file->file_path, 'M');
        }

        if (isset($request->file)) {
            $requestedFileSize = $request->file('file')->getSize() / 1024 / 1024;
        }
        if (isset($request->file_id)) {
            $file = $this->findByIdOrFail(File::class, 'File', $request->file_id);
            $requestedFileSize = $this->getFileSize($file->file_path, 'M');
        }

        if ($memoryUsage + $requestedFileSize > $planMemory) {
            throw new Exception(
                'No enough space, your free space is : (' . number_format((float)($planMemory - $memoryUsage), 3, '.', '') . ' MB)',
                403
            );
        }

        return $next($request);
    }
}
