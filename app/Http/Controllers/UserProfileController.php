<?php
// app/Http/Controllers/UserProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TimezoneService;

class UserProfileController extends Controller
{
    private $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        $this->timezoneService = $timezoneService;
    }

    public function edit()
    {
        $user = Auth::user();
        $timezones = $this->timezoneService->getCommonTimezones();
        
        return view('profile.edit', compact('user', 'timezones'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'timezone' => 'required|timezone',
            'locale' => 'nullable|string|max:10',
        ]);

        Auth::user()->update($validated);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Auto-detect and set timezone from IP
     */
    public function detectTimezone(Request $request)
    {
        $ipAddress = $request->ip();
        $timezoneData = $this->timezoneService->detectFromIp($ipAddress);

        Auth::user()->update([
            'timezone' => $timezoneData['timezone'],
            'country_code' => $timezoneData['country_code'],
        ]);

        return response()->json([
            'success' => true,
            'timezone' => $timezoneData['timezone'],
            'country' => $timezoneData['country'],
        ]);
    }
}