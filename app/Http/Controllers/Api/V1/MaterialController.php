<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index($stageId)
    {
        $materials = Material::where('stage_id', $stageId)
            ->orderBy('order_index')
            ->get();

        return response()->json(['success' => true, 'data' => $materials]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'content_markdown' => 'required|string',
            'image_url' => 'nullable|url',
            'order_index' => 'integer',
        ]);

        $material = Material::create($request->all());
        return response()->json(['success' => true, 'data' => $material], 201);
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $material->update($request->all());
        return response()->json(['success' => true, 'data' => $material]);
    }

    public function destroy($id)
    {
        Material::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Material deleted']);
    }
}
