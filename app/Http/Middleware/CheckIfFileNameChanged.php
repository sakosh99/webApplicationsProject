<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;

class CheckIfFileNameChanged
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
        $file = $this->findByIdOrFail(File::class, 'File', $request->file_id);

        if ($file->file_name != request()->file->getClientOriginalName()) {
            throw new Exception(
                'Failed, the modified file name must be the same as the old file name',
                403
            );
        }


        return $next($request);
    }
}
