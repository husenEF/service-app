<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class MediaController extends Controller
{
    function get($type, $filename)
    {
        // dd([$type, $filename]);
        $path = storage_path('public/' . $type . '/' . $filename);
        if (!File::exists($path)) {
            abort(404);
        }
    }
}
