<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    // Assigner un rôle à un utilisateur
    public function assignRole(Request $request, $userId): JsonResponse
    {
        try {
            $request->validate([
                'role' => 'required|string|exists:roles,name',
            ]);

            $user = User::findOrFail($userId);
            $user->assignRole($request->role);

            return response()->json(['message' => 'Role assigned successfully.', 'data' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // Retirer un rôle d'un utilisateur
    public function removeRole(Request $request, $userId): JsonResponse
    {
        try {
            $request->validate([
                'role' => 'required|string|exists:roles,name',
            ]);

            $user = User::findOrFail($userId);
            $user->removeRole($request->role);

            return response()->json(['message' => 'Role removed successfully.', 'data' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // Assigner une permission à un utilisateur
    public function assignPermission(Request $request, $userId): JsonResponse
    {
        try {
            $request->validate([
                'permission' => 'required|string|exists:permissions,name',
            ]);

            $user = User::findOrFail($userId);
            $user->givePermissionTo($request->permission);

            return response()->json(['message' => 'Permission assigned successfully.', 'data' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // Retirer une permission d'un utilisateur
    public function removePermission(Request $request, $userId): JsonResponse
    {
        try {
            $request->validate([
                'permission' => 'required|string|exists:permissions,name',
            ]);

            $user = User::findOrFail($userId);
            $user->revokePermissionTo($request->permission);

            return response()->json(['message' => 'Permission removed successfully.', 'data' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // Synchroniser les rôles d'un utilisateur
    public function syncRoles(Request $request, $userId): JsonResponse
    {
        try {
            $request->validate([
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,name',
            ]);

            $user = User::findOrFail($userId);
            $user->syncRoles($request->roles);

            return response()->json(['message' => 'Roles synchronized successfully.', 'data' => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }
}