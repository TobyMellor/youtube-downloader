<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use Alaouy\Youtube\Youtube;

class DownloadController extends Controller
{
    private $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getRequest()
    {
        return $this->request;
    }

    public function getDownloadable()
    {
        $request = $this->getRequest();

        $data = [
            'youtube_url' => $request->get('youtube_url'),
        ];

        $validation = $this->getDownloadableValidator($data);

        if (!$validation->fails()) {
            $youtube = new Youtube('AIzaSyAHAhtMMsml_Rm9LkzCWkUEt79E_VoEgSE');

            $videoId = $youtube->parseVidFromURL($data['youtube_url']);
            $video = $youtube->getVideoInfo($videoId);

            if ($video != false) {
                return response()->json([
                    'error' => 0,
                    'message' => 'The video url was valid and the video exists.',
                    'video' => $video
                ]);
            }
        }

        return response()->json([
            'error' => 1,
            'message' => 'The url entered was invalid or that video doens\'t exist.'
        ]);
    }

    public function getDownload($youtubeId)
    {
        $request = $this->getRequest();

        $data = [
            'youtube_id' => $youtubeId,
            'quality' => $request->get('quality')
        ];

        $validation = $this->getDownloadValidator($data);

        if (!$validation->fails()) {
            $requestedQuality = $this->getDownloadQuality($data['quality']);

            if ($requestedQuality == 'best') {
                $command = 'cd ' . public_path('media/') . ' && youtube-dl https://www.youtube.com/watch?v=' . $data['youtube_id'] . ' --id --recode-video mp4';
            } else {
                $command = 'cd ' . public_path('media/') . ' && youtube-dl -f "best[height=' . $requestedQuality . ']" https://www.youtube.com/watch?v=' . $data['youtube_id'] . ' --id --recode-video mp4';
            }

            shell_exec($command); // TODO: Escape shell TODO: Heights may vary

            return response()->download(public_path('media/' . $data['youtube_id'] . '.mp4'));
        } else {
            var_dump($validation->errors());
        }
    }

    private function getDownloadQuality($downloadQuality)
    {
        switch ($downloadQuality) {
            case 'hd2160':
                return '2160';
            case 'hd1440':
                return '1440';
            case 'hd1080':
                return '1080';
            case 'hd720':
                return '720';
            case 'large':
                return '480';
            case 'medium':
                return '360';
            case 'small':
                return '240';
            case 'tiny':
                return '144';
            default:
                return 'best';
        }
    }

    /**
     * Validates an array of information.
     *
     * @return Validator
     */
    protected function getDownloadableValidator(array $data)
    {
        return Validator::make($data, [
            'youtube_url' => 'required|url'
        ]);
    }

    /**
     * Validates an array of information.
     *
     * @return Validator
     */
    protected function getDownloadValidator(array $data)
    {
        return Validator::make($data, [
            'youtube_id' => 'required|size:11',
            'quality' => 'required|in:hd2160,hd1440,hd1080,hd720,large,medium,small,tiny,best'
        ]);
    }
}