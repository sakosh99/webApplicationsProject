<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;
use Illuminate\Support\Facades\Auth;

class CheckFilePublisher
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

        if ($file->publisher_id != Auth::user()->id) {
            throw new Exception(
                'You don\'t have permission',
                403
            );
        }

        return $next($request);
    }
}
