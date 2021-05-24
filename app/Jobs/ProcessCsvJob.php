<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendMail;
use App\Models\Module;

class ProcessCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filePath = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($this->path);
        $file = file($filePath);
        $data = array_map('str_getcsv', $file);
        $header = $data[0];
        unset($data[0]);
        if (!$this->validateFile($data, $header)) {
            return;
        }
        $validator = Validator::make($data, config('app.csv_validations'), config('app.validation_messages'));
        if ($validator->fails()) {
            $messages = $validator->errors()->messages();
            $errorData = [];
            foreach ($messages as $key => $value) {
                $KeyValues = explode(".", $key);
                $errorData = $this->getGroupedMessage($KeyValues, $errorData, $value[0]);
            }
            $details = [
                'body' => $errorData
            ];
            Mail::to(config('app.error_mail'))->send(new SendMail($details));
        } else {
            foreach ($data as $row) {
                $datas[] = array_combine($header, $row);
            }
            Module::insert($datas);
        }
    }

    /**
     * @param $KeyValues
     * @param array $errorData
     * @param $value
     * @return array
     */
    public function getGroupedMessage($KeyValues, array $errorData, $value): array
    {
        $errorData[$KeyValues[1]] = isset($errorData[$KeyValues[1]]) ? $errorData[$KeyValues[1]] . ' , ' . $KeyValues[0] : $value . ' at rows ' . $KeyValues[0];
        return $errorData;
    }

    /**
     * @param array $data
     * @param $header
     * @return int
     */
    public function validateFile(array $data, $header): bool
    {
        $filevalidation = [
            'record' => count($data),
            'column_count' => count($header),
            'head1' => $header[0],
            'head2' => $header[1],
            'head3' => $header[2],
        ];
        $validator = Validator::make($filevalidation, config('app.csv_file_validations'), config('app.csv_file_validations_messages'));
        if ($validator->fails()) {
            $fileErrors = $validator->errors()->messages();
            $error = [];
            foreach ($fileErrors as $errors) {
                $error[] = $errors[0];
            }
            $details = [
                'body' => $error,
            ];
            Mail::to(config('app.error_mail'))->send(new SendMail($details));
            return false;
        } else {
            return true;
        }
    }
}
