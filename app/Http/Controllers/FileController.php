<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessCsvJob;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{

    public function uploadFile(Request $request)
    {
        if ($request->has('csvfile')) {
            $path = $request->file('csvfile')->store('csvfiles');
            ProcessCsvJob::dispatch($path);
            return response()->json([
                'message' => 'File Sucessfully Updated'
            ]);
        } else {
            return response()->json([
                'message' => 'Please Upload File'
            ]);
        }

    }

}
