<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Downloads the selected file from the selected disk
     *
     * Used to allow gates on download paths instead of always using public disk for easy downloads.
     *
     * @param String $path // the relative path to the file download
     * @param String $disk // the disk used to store the file (default = local)
     * @return void
     */
    public function download(String $path, String $disk = 'local')
    {
        return Storage::disk($disk)->download($path);
    }
}
