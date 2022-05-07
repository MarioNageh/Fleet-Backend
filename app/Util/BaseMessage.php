<?php


namespace App\Util;


use Symfony\Component\HttpFoundation\Response;

class BaseMessage
{
    public $messageAr;
    public $messageEn;
    public $code;
    public $data;

    public function __construct($messageEn, $messageAr, $code = 200, $data = [])
    {
        $this->messageAr = $messageAr;
        $this->messageEn = $messageEn;
        $this->code = $code;
        $this->data = $data;

    }

    public static function someThingWrong()
    {
        $baseMessage = new BaseMessage("Error Occur", "حدث خطا ما", 500);
        return $baseMessage->toJson();
    }

    public static function unAuthenticated()
    {
        $baseMessage = new BaseMessage("You Must Login", "يجب تسجيل الدخول", 401);
        return $baseMessage->toJson();
    }

    public function toJson($isArray=false)
    {
        if( gettype($this->data)=="object" || $isArray)
        return response()->json(array_merge([
            'MessageAr' => $this->messageAr,
            'MessageEn' => $this->messageEn,
            'Code' => $this->code
        ],["Data" => $this->data]), $this->code);
        else
        return response()->json(array_merge([
            'MessageAr' => $this->messageAr,
            'MessageEn' => $this->messageEn,
            'Code' => $this->code
        ],$this->data), $this->code);
    }

}
