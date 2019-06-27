<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //
    function errorValidationMessage()
    {
        return [
            'required' => ":attribute harus di isi",
            'unique' => ":attribute sudah pernah di isikan",
            "mimes" => "harus format: :values",
            "int" => "harus format angka",
            "max" => [
                'numeric' => ':attribute tidak boleh lebih dari :max.',
                'file' => ':attribute tidak boleh lebih dari :max kilobytes.',
                'string' => ':attribute tidak boleh lebih dari :max karakter.',
                'array' => ':attribute tidak boleh lebih dar :max buah.',
            ],
            'alpha_dash' => ':attribute hanya boleh text, angka, and garis bawah (_).',
            'string'=>":attribute harus dalam bentuk text",
            "min" => [
                'numeric' => ':attribute tidak boleh kurang dari :min.',
                'file' => ':attribute tidak boleh kurang dari :min kilobytes.',
                'string' => ':attribute tidak boleh kurang dari :min karakter.',
                'array' => ':attribute tidak boleh kurang dari :min buah.',
            ],
        ];
    }
}
