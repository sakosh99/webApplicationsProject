<?php

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupNameConflict
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $isExist = false;
        if (request()->group_type == 'private') {
            if (
                Group::where('group_name', request()->group_name)
                ->where('publisher_id', Auth::user()->id)->first()
            ) {
                $isExist = true;
            }
        } else {
            if (
                Group::where('group_name', request()->group_name)
                ->where('group_type', request()->group_type)->first()
            ) {
                $isExist = true;
            }
        }
        
        if ($isExist == true) {
            throw new Exception(
                '(' . request()->group_name . ') group is already exists, Choose another name',
                403
            );
        }
        return $next($request);
    }
}
