<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBadgeRequest;
use App\Http\Requests\Admin\UpdateBadgeRequest;
use App\Models\Badge;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BadgeWebController extends Controller
{
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
        Badge::create($request->validated());

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
        $badge->update($request->validated());

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge berhasil diperbarui!');
    }

    public function destroy(string $id): RedirectResponse
    {
        Badge::findOrFail($id)->delete();

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge berhasil dihapus!');
    }
}
