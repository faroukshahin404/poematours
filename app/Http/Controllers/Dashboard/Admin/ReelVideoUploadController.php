<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\UploadReelVideoRequest;
use App\Models\Reel;
use Illuminate\Http\JsonResponse;

class ReelVideoUploadController extends Controller
{
    public function __invoke(UploadReelVideoRequest $request): JsonResponse
    {
        $path = Reel::storeVideo($request->file('video'));

        return response()->json([
            'path' => $path,
            'url' => asset('storage/'.$path),
        ]);
    }
}
