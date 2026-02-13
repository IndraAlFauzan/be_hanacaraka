<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MaterialResource;
use App\Models\Material;
use App\Services\MaterialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    protected MaterialService $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    /**
     * Get all materials for a stage
     */
    public function index($stageId): JsonResponse
    {
        $materials = Material::where('stage_id', $stageId)
            ->orderBy('order_index')
            ->get();

        return response()->json([
            'success' => true,
            'data' => MaterialResource::collection($materials),
        ]);
    }

    /**
     * Store a new material (Admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'title' => 'required|string|max:255',
            'content_text' => 'nullable|string',
            'content_markdown' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'order_index' => 'integer|min:1',
        ]);

        $material = $this->materialService->createMaterial($validated, $request);

        return response()->json([
            'success' => true,
            'message' => 'Material created successfully',
            'data' => new MaterialResource($material),
        ], 201);
    }

    /**
     * Get a specific material
     */
    public function show($id): JsonResponse
    {
        $material = Material::with('stage')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new MaterialResource($material),
        ]);
    }

    /**
     * Update a material (Admin only)
     */
    public function update(Request $request, $id): JsonResponse
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'stage_id' => 'sometimes|exists:stages,id',
            'title' => 'sometimes|string|max:255',
            'content_text' => 'nullable|string',
            'content_markdown' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'order_index' => 'sometimes|integer|min:1',
        ]);

        $material = $this->materialService->updateMaterial($material, $validated, $request);

        return response()->json([
            'success' => true,
            'message' => 'Material updated successfully',
            'data' => new MaterialResource($material),
        ]);
    }

    /**
     * Delete a material (Admin only)
     */
    public function destroy($id): JsonResponse
    {
        $material = Material::findOrFail($id);

        $this->materialService->deleteMaterial($material);

        return response()->json([
            'success' => true,
            'message' => 'Material deleted successfully',
        ]);
    }
}
