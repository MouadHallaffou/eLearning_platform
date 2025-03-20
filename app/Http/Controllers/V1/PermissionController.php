<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionController extends Controller
{
    // GET /api/V1/permissions : Lister toutes les permissions
    public function index(): JsonResponse
    {
        try {
            $permissions = Permission::all();
            return response()->json(['data' => $permissions]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // POST /api/V1/permissions : Créer une nouvelle permission
    public function store(StorePermissionRequest $request): JsonResponse
    {
        try {
            $permission = Permission::create(['name' => $request->name]);

            return response()->json(['message' => 'Permission created successfully.', 'data' => $permission], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // GET /api/V1/permissions/{id} : Récupérer les détails d'une permission
    public function show($id): JsonResponse
    {
        try {
            $permission = Permission::findOrFail($id);
            return response()->json(['data' => $permission]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Permission not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // PUT /api/V1/permissions/{id} : Modifier une permission
    public function update(UpdatePermissionRequest $request, $id): JsonResponse
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->name = $request->name;
            $permission->save();

            return response()->json(['message' => 'Permission updated successfully.', 'data' => $permission]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Permission not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/V1/permissions/{id} : Supprimer une permission
    public function destroy($id): JsonResponse
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return response()->json(['message' => 'Permission deleted successfully.'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Permission not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }
}