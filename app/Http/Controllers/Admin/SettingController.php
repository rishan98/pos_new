<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        try {
            return view('settings.edit');
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->except('_token');

            foreach ($data as $key => $value) {
                $setting = Setting::firstOrCreate(['key' => $key]);
                $setting->value = $value;
                $setting->save();
            }

            return redirect()->route('settings.index');
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }
}
