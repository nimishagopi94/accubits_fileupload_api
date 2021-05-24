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
            $validator = Validator::make($request->all(),config('app.csv_file_validations'));
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Invalid file format'
                ]);
            }
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
