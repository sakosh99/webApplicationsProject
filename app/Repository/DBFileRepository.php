<?php

namespace App\Repository;

use App\Models\File;
use App\Models\Group;
use App\RepositoryInterface\FileRepositoryInterface;
use App\Traits\ModelHelper;
use Illuminate\Support\Facades\Auth;

class DBFileRepository implements FileRepositoryInterface
{
    use ModelHelper;

    public function create($attributes)
    {
        return File::create($attributes);
    }

    public function update(File $file, $attributes)
    {
        $file->update($attributes);
    }

    public function delete($file)
    {
        return $file->delete();
    }

    public function find($file_id)
    {
        $file = $this->findByIdOrFail(File::class, 'File', $file_id);
        return $file;
    }
    public function findByMultiIDs($files_ids)
    {
        $files = File::whereIn('id', $files_ids)->get();
        return $files;
    }
    public function userFiles()
    {
        $files = File::where('publisher_id', Auth::user()->id)->get();
        return $files;
    }

    public function groupFiles(Group $group)
    {
        return $group->files;
    }

    public function groupFilesForUser(Group $group)
    {
        $files = $group->files->where('publisher_id', Auth::user()->id);
        return $files;
    }
}
