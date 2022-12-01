<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;

class CheckFilesStatus
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
        $files = File::whereIn('id', $request->file_id)->where('status', 'free')->get();

        if (count($files) < count($request->file_id)) {
            throw new Exception(
                'Failed. One of the files you selected is reserved, please try again later',
                403
            );
        }

        return $next($request);
    }
}
