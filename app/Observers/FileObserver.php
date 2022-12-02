<?php

namespace App\Observers;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class FileObserver
{
    /**
     * Handle the File "created" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function created(File $file)
    {
        $group  = $file->group;
        if (Cache::has($group->name)) {
            Cache::put($group->name, $group->files, 60);
        } else {
            Cache::add($group->name, $group->files, 60);
        }
    }

    /**
     * Handle the File "updated" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function updated(File $file)
    {
        $group  = $file->group;
        if (Cache::has($group->name)) {
            Cache::put($group->name, $group->files, 60);
        } else {
            Cache::add($group->name, $group->files, 60);
        }
    }

    /**
     * Handle the File "deleted" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function deleted(File $file)
    {
        $group  = $file->group;
        if (Cache::has($group->name)) {
            Cache::put($group->name, $group->files, 60);
        } else {
            Cache::add($group->name, $group->files, 60);
        }
    }

    /**
     * Handle the File "restored" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function restored(File $file)
    {
        //
    }

    /**
     * Handle the File "force deleted" event.
     *
     * @param  \App\Models\File  $file
     * @return void
     */
    public function forceDeleted(File $file)
    {
        //
    }
}
