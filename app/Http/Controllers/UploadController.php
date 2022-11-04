<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{

    public function store(Request $request) {
        $validation = Validator::make($request->all(), [
            'file' => 'required|max:4096'
        ]);

        if ($validation->fails()){
            return response()->json('File is required', 403);
        }

        $filesProcessed = 0;

        foreach ($request->file as $file) {

            try {
                $response = Http::attach('file', $file)->post('http://localhost:8004/file');
            } catch (ConnectionException $error){
                return response()->json('OCR Microservice is DOWN');
            }
            
            if ($response->status() != 200){
                return response()->json('OCR Microservice Error');
            }

            $text = $response->object()->text;
        
            $filename = time().'_'.$file->getClientOriginalName();
            $fileModel = new File;
            $fileModel->filename = $filename;
            $fileModel->location = 'storage/'.$filename;
            $fileModel->user_id = '1';
            $fileModel->text = $text;
            $fileModel->save();

            $filesProcessed++;

        }  

        return response()->json([
            "{$filesProcessed} Files Uploaded Successfully"
        ]);
    }
}
