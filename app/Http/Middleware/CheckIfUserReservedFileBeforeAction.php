<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;
use Illuminate\Support\Facades\Auth;

class CheckIfUserReservedFileBeforeAction
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

        if ($file->publisher_id == Auth::user()->id && $file->group->group_type == 'private') {
            return $next($request);
        }

        if ($file->status == 'free' && $file->current_reserver_id == null) {
            throw new Exception(
                'Failed, Please reserve the file before doing this action',
                403
            );
        }
        return $next($request);
    }
}
