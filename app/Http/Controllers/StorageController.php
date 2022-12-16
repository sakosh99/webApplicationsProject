<?php

namespace App\Http\Controllers;

use App\Services\StorageService;
use Illuminate\Http\Request;

class StorageController extends Controller
{

    public function __construct(private StorageService $storageService)
    {
    }

    public function getMemoryUsage()
    {
        return $this->successResponse(
            $this->storageService->memoryUsage(),
            'Storage information fetched successfully'
        );
    }
}
