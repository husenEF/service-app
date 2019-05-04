<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class MediaController extends Controller
{
    function get($type, $filename)
    {
        // dd([$type, $filename]);
        $path = storage_path('app/public/' . $type . '/' . $filename);
        if (!File::exists($path)) {
            // return view()
            return response("404 not found", 404);
        }
        
    }
}
