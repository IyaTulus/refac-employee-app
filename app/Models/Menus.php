<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table = 'menus';

    protected $fillable = ['id_menu', 'name', 'type', 'status', 'route_name', 'route_params', 'href', 'icon', 'target', 'sort'];


    public function parent()
    {
        return $this->belongsTo(Menus::class, 'id_menu');
    }

    public function children()
    {
        return $this->hasMany(Menus::class, 'id_menu');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
