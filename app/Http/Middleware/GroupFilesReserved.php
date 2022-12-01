<?php

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;

class GroupFilesReserved
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
        $group = $this->findByIdOrFail(Group::class, 'Group', request()->group_id);
        if ($group->files->where('status', 'reserved')->first()) {
            throw new Exception(
                'Failed, There are currently reserved files. Please try again',
                403
            );
        }
        return $next($request);
    }
}
