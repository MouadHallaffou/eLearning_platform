<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\SyncPermissionsRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    // GET /api/V1/roles : Lister tous les rôles
    public function index(): JsonResponse
    {
        try {
            $roles = Role::all();
            return response()->json(['data' => $roles]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // POST /api/V1/roles : Créer un nouveau rôle
    public function store(StoreRoleRequest $request): JsonResponse
    {
        try {
            $role = Role::create(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json(['message' => 'Role created successfully.', 'data' => $role], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // GET /V1/roles/{id} : Récupérer les détails d'un rôle
    public function show($id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json(['data' => $role]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // PUT /api/V1/roles/{id} : Modifier un rôle
    public function update(UpdateRoleRequest $request, $id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);

            if ($request->has('name')) {
                $role->name = $request->name;
                $role->save();
            }

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json(['message' => 'Role updated successfully.', 'data' => $role]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/V1/roles/{id} : Supprimer un rôle
    public function destroy($id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json(['message' => 'Role deleted successfully.'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // PUT /api/V1/roles/{id}/sync-permissions : Synchroniser les permissions d'un rôle
    public function syncPermissions(SyncPermissionsRequest $request, $id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            $role->syncPermissions($request->permissions);

            return response()->json(['message' => 'Permissions synchronized successfully.', 'data' => $role]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }
}