<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'status',
        'current_reserver_id',
        'publisher_id',
        'group_id'
    ];

    /**
     * Get all of the comments for the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(FileReport::class);
    }
    public function publisher()
    {
        return $this->belongsTo(User::class);
    }
    public function currentReserver()
    {
        return $this->belongsTo(User::class, 'current_reserver_id');
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
