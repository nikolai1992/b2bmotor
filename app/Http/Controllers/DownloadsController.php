<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadsController extends Controller
{
    public function download($file) {
        $file_path = public_path('files/'.$file);
        return response()->download($file_path);
    }
}
