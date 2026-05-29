<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolSettingRequest;
use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolSettingController extends Controller
{
    public function edit(): View
    {
        return view('school-settings.edit', [
            'principalName' => SchoolSetting::getPrincipalName(),
            'principalNip' => SchoolSetting::getPrincipalNip(),
            'pageTitle' => 'Pengaturan Sekolah',
            'activePage' => 'Pengaturan Sekolah',
        ]);
    }

    public function update(StoreSchoolSettingRequest $request): RedirectResponse
    {
        SchoolSetting::set('principal_name', $request->validated()['principal_name']);
        SchoolSetting::set('principal_nip', $request->validated()['principal_nip']);

        return redirect()
            ->route('admin.school-settings.edit')
            ->with('success', 'Pengaturan sekolah berhasil disimpan.');
    }
}
