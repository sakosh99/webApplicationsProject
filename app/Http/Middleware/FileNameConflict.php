<?php

namespace App\Http\Middleware;

use App\Models\File;
use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;
use App\Traits\FileUploader;

class FileNameConflict
{
    use FileUploader;
    use ModelHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {


        $group = null;
        $file = null;
        if (isset(request()->file_id)) {
            $file = $this->findByIdOrFail(File::class, 'File', request()->file_id);
        }
        if (isset(request()->group_id)) {
            $group = $this->findByIdOrFail(Group::class, 'Group', request()->group_id);
        } elseif (isset(request()->file_id) && !isset(request()->group_id)) {
            $group = $this->findByIdOrFail(Group::class, 'Group', $file->group_id);
        }


        if (isset(request()->file) && isset(request()->group_id)) { //upload
            $file_name = request()->file->getClientOriginalName();
        } elseif (isset(request()->file_id) && isset(request()->group_id)) { //move or copy
            $file_name = $file->file_name;
        } elseif (isset(request()->file_name) && isset(request()->file_id)) { //rename
            $file_name = request()->file_name . '.' . $this->getFileExtension($file->file_path);
            error_log($file_name);
        }


        if ($group != null && $file_name != null && $group->files->where('file_name', $file_name)->first()) {
            throw new Exception(
                '(' . $file_name . ') File is already exists in group (' . $group->group_name . '), Please rename file',
                403
            );
        }

        return $next($request);
    }
}
