<?php

namespace CodeDay\Models;

class Ticket extends ClearModel
{
    protected static $cache = false;

    public static function find($ticketId)
    {
        return new self(self::clearGet('/registration/'.urlencode($ticketId)));
    }

    public static function findByEmail($email)
    {
        return new self(self::clearGet('/registration/by-email/'.urlencode($email)));
    }

    public function setParentInfo($age = null, $name = null, $email = null, $phone = null, $secondary_phone = null, $request_loaner = false)
    {
        return self::clearPost('/registration/'.urlencode($this->id).'/parent-info', [
            'age'                    => $age,
            'parent_name'            => $name,
            'parent_email'           => $email,
            'parent_phone'           => $phone,
            'parent_secondary_phone' => $secondary_phone,
            'request_loaner'         => $request_loaner,
        ]);
    }

    public function getIdentJwt()
    {
        $hmac_key = \config('micro.identify_key');
        $head = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode(['aud' => 'micro.srnd.org/identify', 'iss' => 'srnd.org', 'iat' => time(),
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
        ]);

        $content = self::base64UrlEncode($head).'.'.self::base64UrlEncode($payload);
        $hash = self::base64UrlEncode(hash_hmac('sha256', $content, $hmac_key, true));
        return $content.'.'.$hash;
    }

    public function getSigningLink()
    {
        return self::clearGet('/registration/'.urlencode($this->id).'/sign')['url'];
    }

    public function syncWaiver()
    {
        return self::clearGet('/registration/'.urlencode($this->id).'/sync-waiver');
    }

    private static function base64UrlEncode($data)
    {
        $urlSafeData = strtr(base64_encode($data), '+/', '-_');
    
        return rtrim($urlSafeData, '='); 
    } 
}
