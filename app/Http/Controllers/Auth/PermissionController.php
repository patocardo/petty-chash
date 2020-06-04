<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Rules\ZeroOrExists;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{

    /**
     * TODO: add pagination
     */
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
            'data' => Permission::all()
        ]);
    }
    /*
    *   Permissions are supposed to be mutated in code, not interface
    *
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
                "id" => ['required', new ZeroOrExists('permissions', 'id')],
                "name" => "required|string|min:4|max:64",
                "guard_name" => "nullable|string|min:4|max:64"
            ]
        );

        if ($validated->fails()) {
            return response()->json([
                "type" => "error",
                "errors" => $validated->errors()
            ], 400);
        }
        try {
            $permission = $request->input('id') === 0
                ? Permission::create($request->only(["name", "guard_name"]))
                : Permission::findById($request->input('id'));
            if($request->id > 0) {
                $permission->name = $request->input('name');
                $permission->guard_name = $request->input('guard_name');
                $permission->save();
            }
            return response()->json([
                "type" => "success",
                "data" => $permission
            ]);
        } catch (Exception $err) {
            return response()->json([
                "type" => "error",
                "errors" => [$err->message]
            ], 500);
        }

    }

    public function destroy(Request $request)
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
            Permission::destroy($request->input('ids'));
            return response()->json([
                "type" => "success",
                "message" => "Permissions deleted"
            ]);
        } catch(Exception $err) {
            return response()->json([
                "type" => "error",
                "errors" => [$err->message]
            ], 500);
        }

    }
    */
}
