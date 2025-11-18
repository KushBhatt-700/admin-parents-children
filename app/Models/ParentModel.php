<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParentModel extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'birth_date',
        'age',
        'country_id',
        'state_id',
        'city_id',
        'education',
        'occupation',
        'profile_image'
    ];

    public function children()
    {
        return $this->belongsToMany(Child::class, 'child_parent', 'parent_id', 'child_id');
    }
}
