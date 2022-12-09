<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getResponse200($data)
    {
        return response()->json([
            'message' => 'Successful operation',
            'data' => $data,
        ], 200);
    }

    public function getResponseDelete200($resource)
    {
        return response()->json([
            'message' => "Your $resource has been successfully deleted!"
        ], 200);
    }

    public function getResponseUpdate200($resource)
    {
        return response()->json([
            'message' => "Your $resource has been successfully updated!"
        ], 200);
    }

    /**
     * This feature allows you to customize the message for creating and updating a resource
     * $resource - affected object name (book, author, category, editorial, etc.)
     * Possible values for the variable $operation: created or updated.
     */
    public function getResponse201($resource, $operation, $data)
    {
        return response()->json([
            'message' => "Your $resource has been successfully $operation!",
            'data' => $data,
        ], 201);
    }

    public function getResponse400($field)
    {
        return response()->json([
            'message' => "Your petition has a problem in: $field"
        ], 404);
    }

    public function getResponse401()
    {
        return response()->json([
            'message' => "Unauthorized"
        ], 401);
    }

    public function getResponse404()
    {
        return response()->json([
            'message' => "The requested resource is not found"
        ], 404);
    }

    public function getResponse500()
    {
        return response()->json([
            'message' => "Something went wrong, please try again later"
        ], 500);
    }

    public function getResponse_500($errors)
    {
        return response()->json([
            'message' => "Something went wrong, please try again later",
            'errors' => $errors
        ], 500);
    }


    public function removeWhitespace($value)
    {
        return preg_replace('/\s+/', '\u0020', $value);
    }
}
