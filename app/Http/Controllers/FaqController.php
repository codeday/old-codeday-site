<?php

namespace CodeDay\Http\Controllers;

use Symfony\Component\Yaml\Yaml;

class FaqController extends Controller
{
    public function getAll()
    {
        $result = '';
        foreach (glob(resource_path('faq/*.yaml')) as $file) {
            $yaml = Yaml::parse(file_get_contents($file));
            foreach ($yaml as $qa) {
                $result .= "<article><h1>{$qa['q']}</h1><p>{$qa['a']}</p></article>";
            }
        }

        return response($result);
    }

    public function getIndex()
    {
        $q = \Input::get('q');
        if (!$q) {
            return;
        }

        $host = \Config::get('azqna.host');
        $kb = \Config::get('azqna.kb');
        $key = \Config::get('azqna.key');

        $url = "https://$host.azurewebsites.net/qnamaker/knowledgebases/$kb/generateAnswer";

        $opts = ['http' => [
                'method'  => 'POST',
                'header'  => [
                    'Content-type: application/json',
                    "Authorization: EndpointKey $key",
                    'Cache-Control: no-cache',
                ],
                'content' => json_encode(['question' => $q]),
            ],
        ];

        $context = stream_context_create($opts);

        $answer = json_decode(file_get_contents($url, false, $context))->answers[0];
        if ($answer->score == 0) {
            return;
        }

        return $answer->answer;
    }
}
