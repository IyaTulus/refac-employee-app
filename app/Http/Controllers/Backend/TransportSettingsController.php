<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TransportSetting;
use Illuminate\Http\Request;
use jeemce\controllers\AuthTrait;

class TransportSettingsController extends Controller
{
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

    public function update(Request $request)
    {
        $data = $request->validate([
            'base_fare' => ['required', 'numeric', 'min:0'],
        ]);

        $setting = TransportSetting::latest()->first();

        if (!$setting) {
            $setting = TransportSetting::create([
                'base_fare' => $data['base_fare'],
                'created_by' => auth()->id(),
            ]);
        } else {
            $setting->update([
                'base_fare' => $data['base_fare'],
                'updated_by' => auth()->id(),
            ]);
        }

        return redirect()->route('transport-settings.index')->with('success', 'Pengaturan tunjangan berhasil disimpan.');
    }
}
