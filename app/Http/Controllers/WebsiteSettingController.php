<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingController extends Controller
{
    public function index()
    {
        $websiteSettings = WebsiteSetting::first();
        return view('website_settings.index', compact('websiteSettings'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'website_name' => 'required|string|max:255',
            'website_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
            'website_copyright' => 'required|string|max:255',
        ]);

        $websiteSettings = WebsiteSetting::firstOrNew();

        $websiteSettings->website_name = $request->website_name;
        $websiteSettings->website_copyright = $request->website_copyright;

        if ($request->hasFile('website_logo')) {
            if ($websiteSettings->website_logo) {
                Storage::delete('public/' . $websiteSettings->website_logo);
            }
            $websiteSettings->website_logo = $request->file('website_logo')->store('logos', 'public');
        }

        if ($request->hasFile('website_favicon')) {
            if ($websiteSettings->website_favicon) {
                Storage::delete('public/' . $websiteSettings->website_favicon);
            }
            $websiteSettings->website_favicon = $request->file('website_favicon')->store('favicons', 'public');
        }

        $websiteSettings->save();

        return redirect()->back()->with('success', 'Website settings updated successfully.');
    }
}
