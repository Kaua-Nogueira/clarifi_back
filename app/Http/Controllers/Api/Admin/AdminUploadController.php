<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminUploadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate(['files' => ['required', 'array', 'max:12'], 'files.*' => ['required', 'image', 'max:10240']]);
        $directory = public_path('uploads/content-assets');
        if (! is_dir($directory)) mkdir($directory, 0775, true);

        $assets = collect($request->file('files'))->map(function ($file) use ($directory) {
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $file->move($directory, $filename);
            return [
                'url' => rtrim(config('app.url'), '/').'/uploads/content-assets/'.$filename,
                'alt_text' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'kind' => 'image',
            ];
        });

        return response()->json(['data' => $assets], 201);
    }
}
