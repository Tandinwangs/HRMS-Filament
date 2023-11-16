<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function download($file)
    {
        $filePath = storage_path('app/public/' . $file);

        if (!Storage::exists($filePath)) {
            abort(404, 'File not found');
        }

        // Get the contents of the PDF file
        $fileContents = file_get_contents($filePath);

        // Output the contents as a PDF file
        return response($fileContents)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . basename($filePath) . '"');
    }
    
}
