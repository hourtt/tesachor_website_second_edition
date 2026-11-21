<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditorUploadController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        $diskName = config('filesystems.disks.supabase_destination.bucket')
            ? 'supabase_destination'
            : (app()->environment('testing') ? 'public' : config('filesystems.default', 'public'));

        $disk = Storage::disk($diskName);
        $file = $request->file('image');
        $hash = Str::random(40);
        $ext = strtolower($file->guessExtension() ?? $file->getClientOriginalExtension() ?? 'jpg');
        $path = $disk->putFileAs('editor', $file, "{$hash}.{$ext}");

        return response()->json(['url' => $disk->url($path)]);
    }
}
