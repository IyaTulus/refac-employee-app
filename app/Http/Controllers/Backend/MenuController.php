<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use jeemce\models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menu = Menu::query()
            ->whereNull('id_menu')
            ->where('type', 'main')
            ->where('status', 'active')
            ->orderBy('sort')
            ->get();

        $menu->each(function ($item) {
            $item->tree = Menu::query()
                ->where('id_menu', $item->id)
                ->where('status', 'active')
                ->orderBy('sort')
                ->get();
        });

        return MenuResource::collection($menu);
    }
}
