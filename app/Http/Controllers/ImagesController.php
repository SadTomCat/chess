<?php

namespace App\Http\Controllers;

use App\Http\Requests\CkeditorImagesRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class ImagesController extends Controller
{
    /**
     * @param CkeditorImagesRequest $request
     * @return JsonResponse
     */
    public function uploadFromCkeditor(CkeditorImagesRequest $request): JsonResponse
    {
        try {
            $path = $request->file('upload')->store('images');

            if ($path === false) {
                throw new  Exception();
            }

        } catch (Exception $e) {
            return response()->json([
                "error" => [
                    'message' => 'Something went wrong',
                ],
            ], 422);
        }

        return response()->json([
            'url' => asset("storage/$path"),
        ]);
    }
}
