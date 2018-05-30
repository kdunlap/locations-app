<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class State
 * @package App\Models
 */
class State extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'states';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'abbr',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name'         => 'string',
        'slug'         => 'string',
        'abbr'         => 'string',
    ];

    /**
     * A state has many cities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany( City::class );
    }
}