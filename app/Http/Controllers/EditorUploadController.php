<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorUploadController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $path = $request->file('image')->store('editor', 'public');

        return response()->json(['url' => Storage::disk('public')->url($path)]);
    }
}

