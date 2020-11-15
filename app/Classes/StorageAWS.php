<?php


namespace App\Classes;


use Aws\Result;
use Aws\S3\S3Client;
use Illuminate\Http\Request;

class StorageAWS
{
    public function uploadImage(Request $request, string $folder): Result
    {
        $file = $folder . "/" . $request->file('file')->getClientOriginalName();

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION')
        ]);
        $result = $s3->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $folder . "/" . $request->file('file')->getClientOriginalName(),
            'Body' => file_get_contents($request->file('file')),
            'ACL' => 'public-read',
            'ContentType' => $request->file('file')->getMimeType(),
            'CacheControl' => 'max-age'
        ]);


        $s3->waitUntil('ObjectExists', array(
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $file
        ));

        return $result;
    }
}
