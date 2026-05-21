<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use jeemce\helpers\AuthHelper;
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

        $menu = $this->filterTree($menu);

        return MenuResource::collection($menu);
    }

    protected function loadChildren(int $parentId)
    {
        $children = Menu::query()
            ->where('id_menu', $parentId)
            ->where('status', 'active')
            ->orderBy('sort')
            ->get();

        return $this->filterTree($children);
    }

    protected function filterTree($items)
    {
        return $items->map(function ($item) {
            $item->tree = $this->loadChildren($item->id);
            return $item;
        })->filter(function ($item) {
            return AuthHelper::menuAccess($item->id, 'read') || $item->tree->isNotEmpty();
        })->values();
    }
}
