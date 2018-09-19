<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class AppController extends Controller
{
    public function download()
    {
        return response()->download(storage_path('WeightMate.ipa'));
    }
}
