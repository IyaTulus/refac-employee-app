<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TransportSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use jeemce\controllers\CrudTrait;
use jeemce\controllers\AuthTrait;

class TransportSettingsController extends Controller
{
    use CrudTrait;
    use AuthTrait;

    public function __construct()
    {
        $this->middlewareResourceAccess('transport-settings.%');
    }

    public function index()
    {
        $setting = TransportSetting::latest()->first();

        return view('backend.pages.transport-settings.index', compact('setting'));
    }

    public function form(Request $request)
    {
        $setting = TransportSetting::latest()->first();

        if ($request->isMethod('get')) {
            return view('backend.pages.transport-settings.index', compact('setting'));
        }

        $data = $request->validate([
            'base_fare' => ['required', 'numeric', 'min:0'],
        ]);

        if (! $setting) {
            $setting = TransportSetting::create([
                'base_fare' => $data['base_fare'],
                'created_by' => Auth::id(),
            ]);
        } else {
            $setting->update([
                'base_fare' => $data['base_fare'],
                'updated_by' => Auth::id(),
            ]);
        }

        return redirect()->route('transport-settings.index')->with('success', 'Pengaturan tunjangan berhasil disimpan.');
    }
}
