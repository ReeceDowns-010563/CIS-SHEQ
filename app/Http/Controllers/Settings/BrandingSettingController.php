<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\BrandingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BrandingSettingController extends Controller
{
    public function edit()
    {
        $branding = BrandingSetting::first() ?? new BrandingSetting();
        return view('settings.branding.index', compact('branding'));
    }

    public function update(Request $request)
    {
        $branding = BrandingSetting::first() ?? new BrandingSetting();

        $data = $request->validate([
            'light_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'dark_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:2048',
            'primary_colour' => 'required|string|max:50',
            'secondary_colour' => 'required|string|max:50',
        ]);

        // Create uploads directory if it doesn't exist
        $uploadPath = public_path('uploads/branding');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Handle light logo upload
        if ($request->hasFile('light_logo')) {
            // Delete old light logo if it exists
            if ($branding->light_logo && File::exists(public_path($branding->light_logo))) {
                File::delete(public_path($branding->light_logo));
                \Log::info('Deleted old light logo: ' . $branding->light_logo);
            }

            $file = $request->file('light_logo');
            $fileName = 'light_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);
            $branding->light_logo = 'uploads/branding/' . $fileName;
            \Log::info('Light logo uploaded: ' . $branding->light_logo);
        }

        // Handle dark logo upload
        if ($request->hasFile('dark_logo')) {
            // Delete old dark logo if it exists
            if ($branding->dark_logo && File::exists(public_path($branding->dark_logo))) {
                File::delete(public_path($branding->dark_logo));
                \Log::info('Deleted old dark logo: ' . $branding->dark_logo);
            }

            $file = $request->file('dark_logo');
            $fileName = 'dark_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);
            $branding->dark_logo = 'uploads/branding/' . $fileName;
            \Log::info('Dark logo uploaded: ' . $branding->dark_logo);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon if it exists
            if ($branding->favicon && File::exists(public_path($branding->favicon))) {
                File::delete(public_path($branding->favicon));
                \Log::info('Deleted old favicon: ' . $branding->favicon);
            }

            $file = $request->file('favicon');
            $fileName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);
            $branding->favicon = 'uploads/branding/' . $fileName;
            \Log::info('Favicon uploaded: ' . $branding->favicon);
        }

        // Update colors
        $branding->primary_colour = $data['primary_colour'];
        $branding->secondary_colour = $data['secondary_colour'];

        $saved = $branding->save();
        \Log::info('Branding saved successfully', ['saved' => $saved]);

        return redirect()->back()->with('success', 'Branding updated successfully.');
    }
}
