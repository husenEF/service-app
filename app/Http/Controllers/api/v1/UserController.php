<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct() {
    
    }

    public function get() {
      return response()->json(['success' => true, 'message' => 'Halo']);
    }
}
