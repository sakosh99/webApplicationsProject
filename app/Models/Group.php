<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'group_type',
        'publisher_id'
    ];

    /**
     * Get the user that owns the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publisher()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    /**
     * Get all of the comments for the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * The roles that belong to the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'users_groups',
            'group_id',
            'user_id'
        );
    }
    public function scopeDynamicSearch($query, $filter)
    {
        $query->when(!empty($filter) && $filter == 'published', function ($query) use ($filter) {
            $query->where('publisher_id', Auth::user()->id);
        });

        $query->when(!empty($filter) && $filter == 'joined', function ($query) use ($filter) {
            $query->WhereHas('users', function ($q) {
                $q->where('user_id', Auth::user()->id);
            });
        });

        $query->when(!empty($filter) && $filter == 'all', function ($query) use ($filter) {
            $query->Where('publisher_id', null)
                ->orWhere('publisher_id', Auth::user()->id)
                ->orWhereHas('users', function ($q) {
                    $q->where('user_id', Auth::user()->id);
                })->orderBy('publisher_id');
        });
    }
}
