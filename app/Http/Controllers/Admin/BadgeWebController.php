<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeWebController extends Controller
{
    public function index()
    {
        $badges = Badge::withCount('users')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.badges.index', compact('badges'));
    }

    public function create()
    {
        return view('admin.badges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'icon_url' => 'required|string|max:100',
            'criteria_type' => 'required|string|max:50',
            'criteria_value' => 'nullable|integer|min:0',
            'xp_reward' => 'required|integer|min:0',
        ]);

        Badge::create($validated);
        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $badge = Badge::with('users')->findOrFail($id);
        return view('admin.badges.edit', compact('badge'));
    }

    public function update(Request $request, string $id)
    {
        $badge = Badge::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
            'icon_url' => 'required|string|max:100',
            'criteria_type' => 'required|string|max:50',
            'criteria_value' => 'nullable|integer|min:0',
            'xp_reward' => 'required|integer|min:0',
        ]);

        $badge->update($validated);
        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge berhasil dihapus!');
    }
}
