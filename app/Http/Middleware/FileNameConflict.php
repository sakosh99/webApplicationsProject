<?php

namespace App\Http\Middleware;

use App\Models\File;
use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;

class FileNameConflict
{
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


        if (isset(request()->group_id)) {
            $group = $this->findByIdOrFail(Group::class, 'Group', request()->group_id);
        } else {
            $group = null;
        }

        if (isset(request()->file)) {
            $file_name = request()->file->getClientOriginalName();
        } elseif (isset(request()->file_id)) {
            $file = $this->findByIdOrFail(File::class, 'File', request()->file_id);
            $file_name = $file->file_name;
        } else {
            $file_name = null;
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
