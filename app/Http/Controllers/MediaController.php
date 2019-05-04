<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    function get($type, $filename)
    {
        $path = storage_path('app/public/' . $type . '/' . $filename);
        if (!File::exists($path)) {
            return response("404 not found", 404);
        }
        $ext = File::extension($path);
        $get = File::get($path);
        
        return response($get)
            ->header('Content-Type', "image/$ext")
            ->header('Pragma', 'public')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=60, must-revalidate');
    }
}
