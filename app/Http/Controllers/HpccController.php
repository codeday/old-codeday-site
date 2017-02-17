<?php
namespace CodeDay\Http\Controllers;
use Abraham\TwitterOAuth\TwitterOAuth;

class HpccController extends Controller {
    public function getScreenName()
    {
        if (!\Session::get('oauth_token')) return null;

        $twitter = new TwitterOAuth(
            \config('twitter.consumer_key'),
            \config('twitter.consumer_secret'),
            \Session::get('oauth_token'),
            \Session::get('oauth_token_secret')
        );

        $cred = $twitter->get('account/verify_credentials');
        if (isset($cred->errors) && count($cred->errors) > 0) return null;

        return $cred->screen_name;
    }

    public function getIndex()
    {
        return \View::make('hpcc', [
            'twitter' => $this->getScreenName()
        ]);
    }

    public function getOauthstart()
    {
        $twitteroauth = new TwitterOAuth(\config('twitter.consumer_key'), \config('twitter.consumer_secret'));
        $request_token = $twitteroauth->oauth(
            'oauth/request_token', [
                'oauth_callback' => \url('/hpcc/oauthend')
            ]
        );
        \Session::put('oauth_token', $request_token['oauth_token']);
        \Session::put('oauth_token_secret', $request_token['oauth_token_secret']);
        $url = $twitteroauth->url(
            'oauth/authorize', [
                'oauth_token' => $request_token['oauth_token']
            ]
        );

        return \Redirect::to($url);
    }

    public function getOauthend()
    {
        if (!\Input::get('oauth_verifier') || !\Session::get('oauth_token_secret')) {
            return \Redirect::to('/hpcc/oauthstart');
        }

        $twitteroauth = new TwitterOAuth(
            \config('twitter.consumer_key'),
            \config('twitter.consumer_secret'),
            \Session::get('oauth_token'),
            \Session::get('oauth_token_secret')
        );
        $token = $twitteroauth->oauth(
            'oauth/access_token', [
                'oauth_verifier' => \Input::get('oauth_verifier')
            ]
        );

        \Session::set('oauth_token', $token['oauth_token']);
        \Session::set('oauth_token_secret', $token['oauth_token_secret']);

        return \Redirect::to('/hpcc');
    }

    public function getTweets()
    {
        $twitter = new TwitterOAuth(
            \config('twitter.consumer_key'),
            \config('twitter.consumer_secret'),
            \Session::get('oauth_token'),
            \Session::get('oauth_token_secret')
        );


        $me = $this->getScreenName();
        if (!$me) return \Redirect::to('/hpcc/oauthstart');

        $statuses = $twitter->get('/statuses/user_timeline', ['count' => 200, 'include_rts' => false]);
        $csv = array_map(function($x){
            return [
                substr($x->id, 6),
                date('Ymd', strtotime($x->created_at)),
                $x->in_reply_to_screen_name,
                preg_replace("/[^A-Za-z0-9\.\,\!\?\:\/\-\_\'\[\]\(\)\@ ]/", '', $x->text)
            ];
        }, $statuses);

        $stream = fopen('php://memory','r+');
        foreach ($csv as $line) {
            fputcsv($stream, $line);
        }
        rewind($stream); 

        return (new \Illuminate\Http\Response(stream_get_contents($stream), 200))
            ->header('Content-type', 'text/csv')
            ->header('Content-disposition', 'attachment;filename='.$me.'.csv');
    }
} 
