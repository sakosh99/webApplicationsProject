<?php

namespace App\Traits;

use Exception;
use App\Models\CarImage;

use Illuminate\Support\Facades\Lang;


trait ModelHelper
{
    protected function findByIdOrFail($modelClass, $modelName, $modelId)
    {
        $model = $modelClass::find($modelId);

        if (!$model) {

            throw new Exception(
                $modelName . ' Not Found',
                404
            );
        }
        return $model;
    }
}
