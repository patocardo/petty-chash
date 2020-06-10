<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Rules\ZeroOrExists;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index()
    {
        if (! Gate::allows('authorization')) {
            return response()->json([
                'type' => 'error',
                'errors' => ['not allowed']
            ], 401);
        };
        return response()->json([
            'type' => 'success',
            'data' => Role::with('permission')->all()
        ]);
    }

    public function upsert(Request $request)
    {
        if (! Gate::allows('authorization')) {
            return response()->json([
                'type' => 'error',
                'errors' => ['not allowed']
            ], 401);
        };
        $validated = Validator::make(
            $request->all(),
            [
                "id" => ['required', new ZeroOrExists('roles', 'id')],
                "name" => "required|string|min:4|max:64",
                "guard_name" => "nullable|string|max:64",
                "permissions" => "nullable|array",
                "permissions.*" => "required|number|exists:permissions,id"
            ]
        );

        if ($validated->fails()) {
            return response()->json([
                "type" => "error",
                "errors" => $validated->errors()
            ], 400);
        }
        try {
            if ($request->id === 0) {
                $role = Role::create($request->only(["name", "guard_name"]));
            } else {
                $role = Role::findById($request->id);
                $role->name = $request->name;
                $role->guard_name = $request->guard_name;
                $role->save();
            }
            if($request->permissions->count()) {
                $permissions = Permission::whereIn('id', $request->permissions->toArray());
                $role->syncPermissions($permissions);
            }
            return response()->json([
                "type" => "success",
                "data" => $role
            ]);
        } catch(Exception $err) {
            return response()->json([
                "type" => "error",
                "errors" => [$err->message]
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                "ids" => 'required|array',
                'ids.*' => "required|numeric|min:0"
            ]
        );
        if ($validated->fails()) {
            return response()->json([
                "type" => "error",
                "errors" => $validated->errors()
            ], 400);
        }
        try {
            Role::destroy($request->ids);
            return response()->json([
                "type" => "success",
                "message" => "Roles deleted"
            ]);
        } catch(Exception $err) {
            return response()->json([
                "type" => "error",
                "errors" => [$err->message]
            ], 500);
        }
    }
}
