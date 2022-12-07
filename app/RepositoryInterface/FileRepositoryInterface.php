<?php

namespace App\RepositoryInterface;

use App\Models\File;
use App\Models\Group;

interface FileRepositoryInterface
{
    public function find($file_id);
    public function findByMultiIDs($files_ids);
    public function create($attributes);
    public function update(File $file, $attributes);
    public function delete(File $file);
    public function userFiles();
    public function groupFiles(Group $group);
    public function groupFilesForUser(Group $group);
}
