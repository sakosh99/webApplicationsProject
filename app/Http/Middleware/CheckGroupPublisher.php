<?php

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;
use Illuminate\Support\Facades\Auth;

class CheckGroupPublisher
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
        if (isset($request->group_id)) {
            $group = $this->findByIdOrFail(Group::class, 'Group', $request->group_id);
            if ($group->publisher_id != Auth::user()->id) {
                throw new Exception(
                    'You don\'t have permission',
                    403
                );
            }
        }
        return $next($request);
    }
}
