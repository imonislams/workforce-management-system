<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(protected SettingsService $settingsService) {}

    public function index(): View
    {
        $settings = $this->settingsService->getAll();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string'],
            'office_start_time' => ['nullable', 'date_format:H:i'],
            'office_end_time' => ['nullable', 'date_format:H:i'],
            'grace_period' => ['nullable', 'integer', 'min:0', 'max:120'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'date_format' => ['nullable', 'string', 'max:50'],
            'currency' => ['nullable', 'string', 'max:10'],
        ]);

        $this->settingsService->setMultiple($validated);

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }
}
