<?php

namespace App\Services;

class OTPService implements Interfaces\SendSmsInterface
{


    /////////////////////////////////////////////////////////////////////
    //  . مؤسسة المدار التقني
    //  Mobile.Net.Sa بوابة الإرسال API مكتبة جميع دوال
    //  https://mobile.net.sa/api.html
    /////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////
    //  دالة فحص الرصيد
    //////////////////////////////////////////////////////
//    public function GetCredits($UserName,$UserPassword,$xml=""){
//         @$url = "https://mobile.net.sa/sms/gw/Credits.php?userName=".$UserName."&userPassword=".$UserPassword."&By=standard".$xml;
//         if (!(@$fp =fopen($url,"r"))){
//             $FainalResult = "Erorr Connecting to Gateway.";
//         }else{
//             @$FainalResult =@fread(@$fp,8192);
//             @fclose(@$fp);
//         }
//         return $FainalResult;
//     }


    //////////////////////////////////////////////////////
    //  دالة إرسال الرسالة
    //////////////////////////////////////////////////////

    ////////////ملاحظة : لابد من ارسال بعد ساعة اذا كان (نص الرسالة ، ورقم الجوال ، واسم المرسل ) هو نفسه //////////////
    ////////////ملاحظة : يتم الإرسال بإسم المرسل المعتمد في حسابك ولا يمكن الإرسال بأي اسم مرسل غير مرخص لك //////////////

    // public  function SendSms($UserName,$UserPassword,$Numbers,$Originator,$Message,$infos="",$xml=""){
    //     $url = "https://mobile.net.sa/sms/gw/";
    //    //$url="";
    //     if(!$url || $url==""){
    //         return "No URL";
    //     }else{
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL,$url);
    //         curl_setopt ($ch, CURLOPT_HEADER, false);
    //         curl_setopt ($ch, CURLOPT_POST, true);
    //         $dataPOST = array('userName' => $UserName, 'userPassword' => $UserPassword, 'userSender' => $Originator, 'numbers' => $Numbers, 'msg' => $Message, 'By' => "standard".$infos.$xml);
    //         curl_setopt ($ch, CURLOPT_POSTFIELDS, $dataPOST);
    //         curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //         curl_setopt($ch, CURLOPT_VERBOSE, 0);
    //         curl_setopt($ch, CURLOPT_FAILONERROR,1);
    //         $FainalResult = curl_exec ($ch);
    //         curl_close ($ch);
    //         return $FainalResult;
    //     }

    // }
    public function SendSMS($UserName, $UserPassword, $Numbers, $Originator, $Message, $infos = "", $xml = ""){
        $url = "https://mobile.net.sa/sms/gw/";

        if(!$url || $url == ""){
            return "No URL";
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            $dataPOST = array(
                'userName' => $UserName,
                'userPassword' => $UserPassword,
                'userSender' => $Originator,
                'numbers' => $Numbers,
                'msg' => $Message,
                'By' => "standard" . $infos . $xml
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPOST);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            $FainalResult = curl_exec($ch);
            curl_close($ch);
            return $FainalResult;
        }
    }


    //////////////////////////////////////////////////////
    //  دالة عرض أسماء المرسل
    //////////////////////////////////////////////////////
    // public  function GetSendernames($UserName,$UserPassword,$xml=""){
    //     @$url = "https://mobile.net.sa/sms/gw/Sender.php?userName=".$UserName."&userPassword=".$UserPassword."&By=standard".$xml;
    //     if (!(@$fp =fopen($url,"r"))){
    //         $FainalResult = "Erorr Connecting to Gateway.";
    //     }else{
    //         @$FainalResult =@fread(@$fp,100000);
    //         @fclose(@$fp);
    //     }
    //     return $FainalResult;
    // }



    //////////////////////////////////////////////////////
    //   دالة إنشاء مجموعة
    //////////////////////////////////////////////////////
    // public   function CreateCat($UserName,$UserPassword,$catname){
    //       @$url = "https://mobile.net.sa/sms/gw/cat.php?userName=".$UserName."&userPassword=".$UserPassword."&catname=".urlencode($catname);
    //           if (!(@$fp =fopen($url,"r"))){
    //                $FainalResult = "Erorr Connecting to Gateway.";
    //                                                   }else{
    //              @$FainalResult =@fread(@$fp,50);
    //               @fclose(@$fp);
    //              @$FainalResult=(integer)str_replace(" ","",@$FainalResult);
    //                                                   }
    // return $FainalResult;
    // }
    /////////////////////// نهاية الدالة ///////////////////////



    //////////////////////////////////////////////////////
    //   دالة إضافة رقم واسم إلى المجموعة
    //////////////////////////////////////////////////////
    // public  function CreateNumberAndName($UserName,$UserPassword,$catid,$number,$name){
    // $url = "https://mobile.net.sa/sms/gw/cat.php?userName=".$UserName."&userPassword=".$UserPassword."&catid=".$catid."&number=".$number."&name=".$name;
    //     if (!(@$fp =fopen($url,"r"))){
    //         $FainalResult = "Erorr Connecting to Gateway.";
    //     }else{
    //         @$FainalResult =@fread(@$fp, 50);
    //         @fclose(@$fp);

    //     }

    //     return $FainalResult;

    // }
    /////////////////////// نهاية الدالة ///////////////////////



    //////////////////////////////////////////////////////
    //   دالة إستعراض أرقام واسماء المجموعة
    //////////////////////////////////////////////////////
    // public  function showID_Name_cat($UserName,$UserPassword){

    //     $url = "https://mobile.net.sa/sms/gw/cat.php?userName=".$UserName."&userPassword=".$UserPassword."&getcat=yes";
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL,$url);
    //     curl_setopt ($ch, CURLOPT_HEADER, false);
    //     curl_setopt ($ch, CURLOPT_POST, true);
    //     curl_setopt ($ch, CURLOPT_POSTFIELDS, "&numbers=$nums&sender=$sender");
    //     curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //     curl_setopt($ch, CURLOPT_VERBOSE, 0);
    //     curl_setopt($ch, CURLE_HTTP_NOT_FOUND,1);
    //     $LastData = curl_exec ($ch);
    //     curl_close ($ch);

    //     return $LastData;

    // }
    /////////////////////// نهاية الدالة ///////////////////////




    //////////////////////////////////////////////////////
    //   دالة إستعراض الأرقام وأسماء الأرقام
    //////////////////////////////////////////////////////
    // public   function showID_Name_numbers($UserName,$UserPassword,$id_cat){

    //     $url = "https://mobile.net.sa/sms/gw/cat.php?userName=".$UserName."&userPassword=".$UserPassword."&id_cat=".$id_cat;
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL,$url);
    //     curl_setopt ($ch, CURLOPT_HEADER, false);
    //     curl_setopt ($ch, CURLOPT_POST, true);
    //     curl_setopt ($ch, CURLOPT_POSTFIELDS, "&numbers=$nums&sender=$sender");
    //     curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //     curl_setopt($ch, CURLOPT_VERBOSE, 0);
    //     curl_setopt($ch, CURLE_HTTP_NOT_FOUND,1);
    //     $LastData = curl_exec ($ch);
    //     curl_close ($ch);

    //     return $LastData;

    // }
    /////////////////////// نهاية الدالة ///////////////////////



    /////////////////////// End Of Functions ///////////////////////

}
