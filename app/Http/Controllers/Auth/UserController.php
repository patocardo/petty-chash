<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {
        return response()->json($request->user());
    }

    static function createOrUpdate(array $data)
    {
        $plain = array_diff_key($data, array_flip(['id', 'password', 'roles']));
        $user = ($data['id'] === 0)
            ? User::create($plain)
            : User::where('id', $data['id'])->update($plain);
        if (isset($data['password']) > 0) {
            $user->password = bcrypt($data['password']);
        }
        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }
        return $user;
    }

    public function upsert(UserRequest $request)
    {
        // Anonymous can create users.
        if ($request->input('id') > 0 && !Gate::allows('user.mutation')) {
            return response()->json([
                'type' => 'error',
                'errors' => ['not allowed']
            ], 401);
        };
        try {
            $user = $this->createOrUpdate($request->all());
            return response()->json([
                "type" => "success",
                "data" => $user
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
        if (! Gate::allows('user.mutation')) {
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
            ]);
        }
        try {
            User::destroy($request->ids);
            return response()->json([
                "type" => "success",
                "message" => "Users deleted"
            ]);
        } catch(Exception $err) {
            return response()->json([
                "type" => "error",
                "errors" => [$err->message]
            ]);
        }

    }
}
