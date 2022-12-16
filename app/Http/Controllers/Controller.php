<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Traits\ApiResponser;
use App\Traits\FileUploader;
use App\Traits\ModelHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        ApiResponser,
        ModelHelper,
        FileUploader;
    public function test()
    {
        return request()->url();
    }
}
