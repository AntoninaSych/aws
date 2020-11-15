<?php


namespace App\Classes;


use Aws\Rekognition\RekognitionClient;

class RekognitionAWS
{
    public function recognition($imageName, $folder)
    {
        $client = new RekognitionClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);


        $result = $client->detectLabels([
            'Image' => [
                'S3Object' => [
                    'Bucket' => env('AWS_BUCKET'),
                    'Name' => $folder . '/' . $imageName,
                ],
            ], 'MinConfidence' => 50
        ]);;
        $s[] = $result->toArray()['Labels'];
        $coincidences = array_map(function ($item) {

            return [
                'Confidence' => $item['Confidence'],
                'Name' => $item['Name']
            ];

        }, $s[0]);

        return $coincidences;
    }

    public function findLabel($coincidences, $word): bool
    {
        $lables = array_filter($coincidences, function ($item) use ($word) {
            return ($item['Name'] === $word);
        });

        if (count($lables) > 0) {
            return true;
        }

        return false;
    }
}
