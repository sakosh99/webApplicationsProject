<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileReport extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'file_id',
        'user_id',
        'action',
        'to_group',
        'old_file_name',
        'new_file_name',
        'created_at'
    ];

    /**
     * Get the user that owns the FileReport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class, 'to_group');
    }
}
