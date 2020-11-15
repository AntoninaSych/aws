<?php

namespace App\Http\Controllers;


use App\Classes\RekognitionAWS;
use App\Classes\StorageAWS;
use App\Mail\MailAWS;
use Aws\Rekognition\RekognitionClient;
use Aws\Result;
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

        $storage = new StorageAWS();
        $imgUrl = $storage->uploadImage($request, $this->folder)->get('ObjectURL');

        $imgExplode = explode("/", $imgUrl);
        $imgName = end($imgExplode);

        $rekognObj = new RekognitionAWS();
        $coincidences = $rekognObj->recognition($imgName, $this->folder);
        $isPresent = $rekognObj->findLabel($coincidences, $this->word);


        if (!$isPresent) {
            Mail::to($request->get('email'))
                ->send(new MailAWS('Upload image results.',$imgUrl, 'Dog not found'));

        }

        return view('show')->with([
            'imgUrl' => $imgUrl,
            'coincidences' => $coincidences,
            'isPresent' => $isPresent
        ]);

    }
}
