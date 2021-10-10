<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ApiController extends Controller
{

    public function index(Request $request)
    {
        try {

            $response = [
                'code' => 500,
                'message' => 'Updating Error',
                'result' => null,
            ];

            $customer = User::paginate(15);
            if ($request->user_id) {
                $customer = $customer->where('id', $request->user_id);
            }
            if ($customer) {
                $response['code'] = 200;
                $response['message'] = 'Customer count {' . $customer->count() . '}';
                $response['result'] = $customer;
            } else {
                $response['code'] = 500;
                $response['message'] = 'Customer not founded';
                $response['result'] = $customer;
            }
            return response()->json(['response' => $response], 500);

        } catch (Exception $ex) {
            $response['message'] = $ex->getMessage();
            $response['result'] = null;
            return response()->json(['response' => $response], 500);
        }
    }

    public function create(Request $request): JsonResponse
    {

        try {
            $response = [
                'code' => 500,
                'message' => 'Creation Error',
                'result' => null,
            ];
            if ($request->name && $request->last_name && $request->email) {
                $create = User::create([
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'birth_date' => $request->birth_date,
                    'password' => Hash::make('11111111'),
                ]);


                if ($create) {
                    $response['code'] = 200;
                    $response['message'] = 'Customer was created successfully';
                    $response['result'] = $create;
                }
                return response()->json(['response' => $response], 200);
            }
        } catch (Exception $ex) {
            $response['message'] = $ex->getMessage();
            $response['result'] = null;
            return response()->json(['response' => $response], 500);
        }
        return response()->json(['response' => $response], 500);
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $response = [
                'code' => 500,
                'message' => 'Updating Error',
                'result' => null,
            ];
            if ($request->user_id) {
                $customer = User::whereId($request->user_id)->first();
                if ($customer) {
                    $customer->update([
                        'name' => $request->name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'birth_date' => $request->birth_date,
                    ]);
                    $response['code'] = 200;
                    $response['message'] = 'Customer was updated successfully';
                    $response['result'] = $customer;
                    return response()->json(['response' => $response]);
                } else {
                    $response['code'] = 500;
                    $response['message'] = 'Customer not founded';
                    $response['result'] = null;
                    return response()->json(['response' => $response], 500);
                }

                $response['code'] = 500;
                $response['message'] = 'User Id not founded';
                $response['result'] = null;
                return response()->json(['response' => $response], 500);
            }
        } catch (Exception $ex) {
            $response['message'] = $ex->getMessage();
            $response['result'] = null;
            return response()->json(['response' => $response], 500);
        }
        return response()->json(['response' => $response], 500);
    }
}
