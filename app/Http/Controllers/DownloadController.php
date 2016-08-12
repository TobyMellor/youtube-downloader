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
                $command = 'pytube -e mp4 ' . escapeshellarg($data['youtube_url']);

                $output = shell_exec($command);

                $startsAt = strpos($output, '[') + 1;
                $endsAt = strpos($output, ']', $startsAt);
                $qualities = substr($output, $startsAt, $endsAt - $startsAt);

                $qualities = explode(',', str_replace([' \'', '\''], '', $qualities));

                return response()->json([
                    'error' => 0,
                    'message' => 'The video url was valid and the video exists.',
                    'video' => $video,
                    'qualities' => $qualities
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
            'quality' => $request->get('quality'),
            'format' => $request->get('format')
        ];

        $validation = $this->getDownloadValidator($data);

        if (!$validation->fails()) {
            @unlink(public_path('media/' . $data['youtube_id'] . '.' . $data['format']));

            $command = 'cd ' . public_path('media/') . ' && pytube -e ' . escapeshellarg($data['format']) . ' -r ' . escapeshellarg($data['quality']) . ' -f ' . escapeshellarg($data['youtube_id']) . ' ' . escapeshellarg('https://www.youtube.com/watch?v=' . $data['youtube_id']);
            
            shell_exec($command);

            return response()->download(public_path('media/' . $data['youtube_id'] . '.' . $data['format']));
        } else {
            var_dump($validation->errors());
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
            'quality' => 'required',
            'format' => 'required'
        ]);
    }
}