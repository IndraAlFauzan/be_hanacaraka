<?php

namespace App\Services;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialService
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Create a new material
     *
     * @param array $data
     * @param Request $request
     * @return Material
     */
    public function createMaterial(array $data, Request $request): Material
    {
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image_url'] = $this->fileUploadService->uploadImage(
                $request->file('image'),
                'materials',
                'material'
            );
        }

        return Material::create($data);
    }

    /**
     * Update a material
     *
     * @param Material $material
     * @param array $data
     * @param Request $request
     * @return Material
     */
    public function updateMaterial(Material $material, array $data, Request $request): Material
    {
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($material->image_url) {
                $this->fileUploadService->deleteImage($material->image_url, 'materials');
            }

            $data['image_url'] = $this->fileUploadService->uploadImage(
                $request->file('image'),
                'materials',
                'material'
            );
        }

        $material->update($data);

        return $material;
    }

    /**
     * Delete a material with its image
     *
     * @param Material $material
     * @return bool
     */
    public function deleteMaterial(Material $material): bool
    {
        // Delete image if exists
        if ($material->image_url) {
            $this->fileUploadService->deleteImage($material->image_url, 'materials');
        }

        return $material->delete();
    }
}
