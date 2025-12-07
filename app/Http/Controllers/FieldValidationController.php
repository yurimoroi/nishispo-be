<?php

namespace App\Http\Controllers;

use App\Http\ApiResponse\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class FieldValidationController extends Controller
{
    public function validateField(Request $request)
    {

        $request->validate([
            'field' => 'required|string',
            'value' => 'required'
        ]);


        $fieldRules = [
            'email' => ['required', 'email', Rule::unique('users', 'email')->withoutTrashed()],
        ];

        $rules = $fieldRules[$request->field] ?? ['required'];

        $validator = Validator::make(['value' => $request->value], ['value' => $rules]);

        if ($validator->fails()) {
            return ApiResponse::error('', 422);
        }
        
        return  ApiResponse::success($request->value);
    }
}
