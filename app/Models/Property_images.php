<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property_images extends Model
{
    protected $fillable = ['property_id', 'path', 'filename'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
