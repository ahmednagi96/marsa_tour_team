<?php

namespace App\Services\Interfaces;

interface SendSmsInterface{

    public function SendSMS($UserName, $UserPassword, $Numbers, $Originator, $Message, $infos = "", $xml = "");
}
