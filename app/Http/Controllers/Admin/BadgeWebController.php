<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBadgeRequest;
use App\Http\Requests\Admin\UpdateBadgeRequest;
use App\Models\Badge;
use App\Services\FileUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BadgeWebController extends Controller
{
    public function __construct(
        private FileUploadService $fileUploadService
    ) {}

    public function index(): View
    {
        $badges = Badge::withCount('users')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.badges.index', compact('badges'));
    }

    public function create(): View
    {
        return view('admin.badges.create');
    }

    public function store(StoreBadgeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Upload icon image
        if ($request->hasFile('icon')) {
            $imageUrl = $this->fileUploadService->uploadImage(
                $request->file('icon'),
                'badges',
                'badge_icon'
            );
            // Extract relative path from full URL
            $data['icon_path'] = str_replace(asset('storage/'), '', $imageUrl);
        }

        unset($data['icon']);
        Badge::create($data);

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge berhasil ditambahkan!');
    }

    public function edit(string $id): View
    {
        $badge = Badge::with('users')->findOrFail($id);

        return view('admin.badges.edit', compact('badge'));
    }

    public function update(UpdateBadgeRequest $request, string $id): RedirectResponse
    {
        $badge = Badge::findOrFail($id);
        $data = $request->validated();

        // Upload new icon if provided
        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($badge->icon_path) {
                Storage::disk('public')->delete($badge->icon_path);
            }

            $imageUrl = $this->fileUploadService->uploadImage(
                $request->file('icon'),
                'badges',
                'badge_icon'
            );
            // Extract relative path from full URL
            $data['icon_path'] = str_replace(asset('storage/'), '', $imageUrl);
        }

        unset($data['icon']);
        $badge->update($data);

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge berhasil diperbarui!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $badge = Badge::findOrFail($id);

        // Delete icon file
        if ($badge->icon_path) {
            Storage::disk('public')->delete($badge->icon_path);
        }

        $badge->delete();

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge berhasil dihapus!');
    }
}
