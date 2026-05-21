<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Throwable;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $href = $this->href;

        // Normalize route params: may be stored as JSON or array
        $params = $this->route_params;
        if (is_string($params) && $params !== '') {
            $decoded = json_decode($params, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $params = $decoded;
            }
        }

        if (empty($href) && filled($this->route_name)) {
            $name = $this->route_name;

            // Try exact named route
            $tried = false;
            try {
                if (Route::has($name)) {
                    $href = route($name, $params ?? []);
                    $tried = true;
                }
            } catch (Throwable $e) {
                // ignore and try fallbacks
            }

            // Try admin.<name>
            if (empty($href)) {
                $adminName = 'admin.' . $name;
                try {
                    if (Route::has($adminName)) {
                        $href = route($adminName, $params ?? []);
                        $tried = true;
                    }
                } catch (Throwable $e) {
                    // ignore
                }
            }

            // Fallback: build path by replacing dots with slashes under /admin
            if (empty($href)) {
                $path = str_replace('.', '/', $name);
                $href = url('/admin/' . ltrim($path, '/'));
            }
        }

        if (empty($href)) {
            $href = url('/admin#');
        }

        return [
            'id' => $this->id,
            'parent_id' => $this->id_menu,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'route_name' => $this->route_name,
            'route_params' => $this->route_params,
            'href' => $href,
            'icon' => $this->icon,
            'target' => $this->target,
            'sort' => $this->sort,
            'children' => !empty($this->tree)
                ? MenuResource::collection($this->tree)
                : [],
        ];
    }
}
