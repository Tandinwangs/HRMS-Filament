<?php

// namespace App\Filament\Actions;

// use Filament\Tables\Actions\Action;
// use Illuminate\Support\Facades\Storage;
// use Symfony\Component\HttpFoundation\StreamedResponse;

// class DownloadFileAction extends Action
// {
//     public static $name = 'download-file';

//     public function handle($record)
//     {
//         $filePath = $record->file_path; // Adjust this depending on your model structure

//         return response()->stream(
//             function () use ($filePath) {
//                 echo Storage::get($filePath);
//             },
//             200,
//             [
//                 'Content-Type' => Storage::mimeType($filePath),
//                 'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"',
//             ]
//         );
//     }
// }

