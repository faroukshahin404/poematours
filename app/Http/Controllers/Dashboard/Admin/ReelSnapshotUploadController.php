<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\UploadReelSnapshotRequest;
use App\Models\Reel;
use Illuminate\Http\JsonResponse;

class ReelSnapshotUploadController extends Controller
{
    public function __invoke(UploadReelSnapshotRequest $request): JsonResponse
    {
        $path = Reel::storeSnapshot($request->file('snapshot'));

        return response()->json([
            'path' => $path,
            'url' => asset('storage/'.$path),
        ]);
    }
}
