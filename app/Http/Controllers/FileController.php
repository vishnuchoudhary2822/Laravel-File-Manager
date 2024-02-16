<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
{
    //

    public function uploadFile(Request $request)
    {
        // Your file upload logic goes here
        // You can access files using $request->file('file') and handle the upload process

        $file = new File();
        $file->name = $request->file('file')->getClientOriginalName();
        $file->path = $request->file('file')->store('files'); // Adjust the storage path as needed
        $file->save();

        return response()->json(['status' => 'success', 'message' => 'File uploaded successfully']);
    }

    public function getFiles(Request $request)
    {
        // Your file retrieval logic
        $files = File::all();

        // Build HTML string
        $htmlResponse = '';
        foreach ($files as $file) {
            $htmlResponse .= '<div class="col-file-manager" id="file_col_id_' . $file->id . '">';
            $htmlResponse .= '<div class="file-box" data-file-id="' . $file->id . '" data-file-name="' . $file->name . '">';
            $htmlResponse .= '<div class="image-container icon-container">';
            $htmlResponse .= '<div class="file-icon file-icon-lg" data-type="' . @pathinfo($file->name, PATHINFO_EXTENSION) . '"></div>';
            $htmlResponse .= '</div>';
            $htmlResponse .= '<span class="file-name">' . $this->limit_character($file->name, 25, "..") . '</span>';
            $htmlResponse .= '</div> </div>';
        }

        // Return both JSON and HTML responses
        return response()->json(['html' => $htmlResponse, 'files' => $files]);
    }


    function limit_character($string, $n, $end = '...')
    {
        if (strlen($string) > $n) {
            $string = substr($string, 0, $n) . $end;
        }
        return $string;
    }
}
