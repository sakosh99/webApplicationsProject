<?php

namespace App\Http\Controllers;

use App\Http\Requests\groups\LeftGroupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['checkRole'])
            ->only('getAllUsers');
    }

    public function getAllUsers()
    {
        $users = User::all();

        return $this->successResponse(
            UserResource::collection($users),
            'Users fetched successfully',
        );
    }
}
