<?php

namespace App\Http\Middleware;

use App\Models\Group;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserReservedFile
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

        if (isset(request()->user_id) && $group->files->where('current_reserver_id', request()->user_id)->first()) {
            throw new Exception(
                'Failed, The user is currently modifying a file. Please try again',
                403
            );
        }

        if (!isset(request()->user_id) && $group->files->where('current_reserver_id', Auth::user()->id)->first()) {
            throw new Exception(
                'Failed, You cannot exit the group before canceling the reservation of the files that you have reserved',
                403
            );
        }
        return $next($request);
    }
}
