<?php

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;

class CheckGroupType
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

        if ($request->routeIs('groupUsers')) {
            if ($group->group_type == 'public') {
                throw new Exception(
                    'This group contains by default all users',
                    403
                );
            }
        } elseif ($group->group_type == 'private') {
            throw new Exception(
                'Failed, you can\'t add members to private group',
                403
            );
        }

        return $next($request);
    }
}
