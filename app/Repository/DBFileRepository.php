<?php

namespace App\Repository;

use App\Models\File;
use App\Models\User;
use App\RepositoryInterface\FileRepositoryInterface;
use App\Traits\ModelHelper;

class DBFileRepository implements FileRepositoryInterface
{
    use ModelHelper;
    public function find($file_id)
    {
        $file = $this->findByIdOrFail(File::class, 'File', $file_id);
        return $file;
    }
}
