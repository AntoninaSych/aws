<?php

namespace App\Http\Controllers;



use App\Mail\MailAWS;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ImageController extends Controller
{
    public $image;
    public $folder = 'img';
    public $storage;
    public $word = 'Dog';


    public function showForm()
    {
        return view('form');
    }

    public function submitForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'file' => 'required|image'

        ]);
        if ($validator->fails()) {
            return view('form')->withError($validator->errors()->first());
        }
        $this->__clearStorage();
        $this->__uploadImage($request);
        $uploadedImage = $this->__getImage();
        $coincidences = $this->__recognition($uploadedImage);
        $isPresent = $this->__findLabel($coincidences, $this->word);
        $imgUrl = env('AWS_URL') . '/' . array_shift($uploadedImage);

        if (!$isPresent) {
            Mail::to($request->get('email'))
                ->send(new MailAWS($imgUrl, 'Dog not found'));

        }

        return view('show')->with([
            'imgUrl' => $imgUrl,
            'coincidences' => $coincidences,
            'isPresent' => $isPresent
        ]);

    }

    private function __findLabel($coincidences, $word): bool
    {
        $lables = array_filter($coincidences, function ($item) use ($word) {
            return ($item['Name'] === $word);
        });

        if (count($lables) > 0) {

            return true;
        }

        return false;
    }

    private function __clearStorage(): void
    {
        Storage::delete(Storage::disk('s3')->allFiles());

    }

    private function __uploadImage(Request $request): void
    {
        $file = $this->folder . "/" . $request->file('file')->getClientOriginalName();

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION')
        ]);
        $result = $s3->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $this->folder . "/" . $request->file('file')->getClientOriginalName(),
            'Body' => file_get_contents($request->file('file')),
            'ACL' => 'public-read',
            'ContentType' => $request->file('file')->getMimeType(),
            'CacheControl' => 'max-age'
        ]);

        $s3->waitUntil('ObjectExists', array(
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $file
        ));

    }

    private function __recognition($storedImages)
    {
        $client = new RekognitionClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);


        foreach ($storedImages as $image) {

            $result = $client->detectLabels([
                'Image' => [
                    'S3Object' => [
                        'Bucket' => env('AWS_BUCKET'),
                        'Name' => $image,
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
    }

    private function __getImage()
    {
        $storedImage = Storage::disk('s3')->allFiles($this->folder);

        return $storedImage;
    }
}
