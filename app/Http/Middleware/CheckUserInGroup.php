<?php

namespace App\Http\Middleware;

use App\Models\Group;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use App\Traits\ModelHelper;
use Exception;
use Illuminate\Support\Facades\Auth;

class CheckUserInGroup
{
    use ModelHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type)
    {
        $userByEmailOrUserName = null;
        if (isset($request->emailOrUserName)) {
            $userByEmailOrUserName = User::where('email', $request->emailOrUserName)
                ->orWhere('user_name', $request->emailOrUserName)
                ->first();
            if (!$userByEmailOrUserName) {
                throw new Exception(
                    'User not found',
                    404
                );
            }
        }

        if (isset($request->group_id)) {
            $group = $this->findByIdOrFail(Group::class, 'Group', $request->group_id);

            $user_id = isset($request->user_id) ? $request->user_id : Auth::user()->id;

            if ($userByEmailOrUserName != null) {
                $user = $group->users->where('id', $userByEmailOrUserName->id)->first();
            } else {
                $user = $group->users->where('id', $user_id)->first();
            }

            if ($type == 'true') {
                if ($user) {
                    throw new Exception(
                        'User already exists in this group',
                        403
                    );
                }
            }
            if ($type == 'false') {
                if (!$user) {
                    throw new Exception(
                        'User doesn\'t exist in this group',
                        403
                    );
                }
            }
        }
        return $next($request);
    }
}
