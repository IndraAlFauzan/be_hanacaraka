<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMaterialRequest;
use App\Http\Requests\Admin\UpdateMaterialRequest;
use App\Models\Material;
use App\Models\Stage;
use App\Services\MaterialService;
use Illuminate\Http\Request;

class MaterialWebController extends Controller
{
    protected MaterialService $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    /**
     * Display a listing of materials.
     */
    public function index(Request $request)
    {
        $query = Material::with(['stage.level']);

        if ($request->filled('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }

        $materials = $query->orderBy('stage_id')->orderBy('order_index')->paginate(20);
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();

        return view('admin.materials.index', compact('materials', 'stages'));
    }

    /**
     * Show the form for creating a new material.
     */
    public function create()
    {
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.materials.create', compact('stages'));
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(StoreMaterialRequest $request)
    {
        $this->materialService->createMaterial($request->validated(), $request);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Display the specified material.
     */
    public function show(string $id)
    {
        $material = Material::with(['stage.level'])->findOrFail($id);
        return view('admin.materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified material.
     */
    public function edit(string $id)
    {
        $material = Material::with(['stage.level'])->findOrFail($id);
        $stages = Stage::with('level')->orderBy('level_id')->orderBy('stage_number')->get();
        return view('admin.materials.edit', compact('material', 'stages'));
    }

    /**
     * Update the specified material in storage.
     */
    public function update(UpdateMaterialRequest $request, string $id)
    {
        $material = Material::findOrFail($id);

        $this->materialService->updateMaterial($material, $request->validated(), $request);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy(string $id)
    {
        $material = Material::findOrFail($id);

        $this->materialService->deleteMaterial($material);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil dihapus!');
    }
}
