<?php
$domain="www.lockchoice.com";

/*
$url = Util::addhttp($domain);
foreach (MagentoVersionMap::load() as $file => $hashs) {
    $md5 = md5(Util::http_get($url . $file));
        $version = $hashs[$md5];
        if ($version) {
            echo $version;
            break;
        }
    }

    */

    $url = Util::addhttp($domain);
        $strHtml = Util::http_get($url . "/downloader");
        $strHtml = str_replace("\n", "", $strHtml);
        $strHtml = str_replace("\r", "", $strHtml);
        
        if(preg_match("/\(Magento Connect Manager([^\)]*)\)/", $strHtml, $arrOut)){
            echo trim($arrOut[1]);
        } else {
            echo false;
        }


    class Util
    {
    // refausa.com  => http://refausa.com
        public static function addhttp($url) {
            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $url = "http://" . $url;
            }
            return $url;
        }

    // get html source, capable of handling redirects
        public static function http_get ($url)
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $content = curl_exec($ch);
            curl_close($ch);

            return $content;
        }

    }


    class MagentoVersionMap
    {

        public static function load()
        {

            return array(

                "/js/lib/flex.js" => array(
                    "054d2e143c970eb7bd06fd8018365a9d"=> "CE 1.1.0", 
                    "1d12e583109a611dff2dfb01f6454ff6"=> "CE 1.9.3.2", 
                    "4040182326f3836f98acabfe1d507960"=> "CE 1.4.0.1", 
                    "8502efe74970465a64079318115b8c0c"=> "CE 1.0", 
                    "9713d594894d117ef6a454463dc5ede0"=> "EE 1.14.3.0", 
                    "9fe92ce6c54deba24c2cd0d484d848cd"=> "EE 1.14.2.0", 
                    "e37eeca21ff013a52085fc4f7bbe3299"=> "CE 1.9.1.0", 
                    "eb84fc6c93a9d27823dde31946be8767"=> "CE 1.4.0.0", 
                    "f1312acc7a5314daf5b2f3e1c4f1ef37"=> "EE 1.14.1.0"

                    ),

                "/js/mage/adminhtml/product.js" => array(
                    "0c2776e2f98445c0f325cd0b42196e67"=> "EE 1.14.2.0", 
                    "12770f4009de359028116ee948f664f9"=> "EE 1.11.0.2", 
                    "356497d3819ccdd9df7e4811bc376dca"=> "CE 1.9.3.2", 
                    "5290e61c41b2d880a93954a579e5ab36"=> "EE 1.14.1.0", 
                    "71ab5165873c747ec18ac28616d43693"=> "CE 1.0", 
                    "7941874630e6f7d6fa1c7b040cd00157"=> "CE 1.9.1.0", 
                    "81d8065e9cee57a5e30ef2622f3a4506"=> "CE 1.6.0.0", 
                    "b67826d2dee4f706dbfa3bbf97208868"=> "EE 1.11.2.0", 
                    "bd85168aa12ea9816488a1fa55e32dce"=> "EE 1.14.3.0", 
                    "d50a6470367a63f6ad50eb84120dffa5"=> "CE 1.1.0", 
                    "e887acfc2f7af09e04f8e99ac6f7180d"=> "CE 1.3.0"
                    ),

                "/js/mage/adminhtml/sales.js" => array(
                    "0e400488c83e63110da75534f49f23f3"=> "CE 1.3.2", 
                    "17da0470950e8dd4b30ccb787b1605f5"=> "CE 1.1.x", 
                    "1cb6e72078c384df2d62745e34060fed"=> "CE 1.9.0.x", 
                    "26c8fd113b4e51aeffe200ce7880b67a"=> "CE 1.8.0.0", 
                    "2adfdc52c344f286283a7ca488ccfcab"=> "CE 1.9.2.x", 
                    "3fe31e1608e6d4f525d5db227373c5a0"=> "EE 1.13.0.x", 
                    "40417cf4bee0e99ffc3930b1465c74ae"=> "EE 1.11.2.0", 
                    "40c6203f5316caec1e10ac3f2bbb23db"=> "EE 1.14.2.0", 
                    "4422dffc16da547c671b086938656397"=> "CE 1.4.2.0", 
                    "48d609bb2958b93d7254c13957b704c4"=> "CE 1.6.x", 
                    "4b4cc67bdb0c87ec0545c23d9afc0df0"=> "CE 1.9.3.2", 
                    "5112f328e291234a943684928ebd3d33"=> "CE 1.1.x", 
                    "5656a8c1c646afaaf260a130fe405691"=> "CE 1.8.1.0", 
                    "720409ee3dec64a678117c488f6b3f47"=> "CE 1.9.3.x", 
                    "7ca2e7e0080061d2edd1e5368915c267"=> "EE 1.10.1.1", 
                    "839ead52e82a2041f937389445b8db04"=> "CE 1.3.3.0", 
                    "86e8bca8057d2dd65ae3379adca0afff"=> "EE 1.14.0.x", 
                    "95e730c4316669f2df71031d5439df21"=> "CE 1.1.0", 
                    "9a5d40b3f07f8bb904241828c5babf80"=> "EE 1.13.1.0", 
                    "a0436f1eee62dded68e0ec860baeb699"=> "CE 1.9.1.0", 
                    "a4296235ba7ad200dd042fa5200c11b0"=> "CE 1.6.0.0", 
                    "a86ad3ba7ab64bf9b3d7d2b9861d93dc"=> "CE 1.0", 
                    "aeb47c8dfc1e0b5264d341c99ff12ef0"=> "EE 1.11.0.2", 
                    "ba43d3af7ee4cb6f26190fc9d8fba751"=> "EE 1.14.1.0", 
                    "bdacf81a3cf7121d7a20eaa266a684ec"=> "CE 1.5.1.0", 
                    "d1bfb9f8d4c83e4a6a826d2356a97fd7"=> "CE 1.3.1", 
                    "d80c40eeef3ca62eb4243443fe41705e"=> "CE 1.5.0.1", 
                    "ebc8928fe532d05a7d485f577eadf31f"=> "EE 1.14.3.0", 
                    "ec6a34776b4d34b5b5549aea01c47b57"=> "EE 1.10.0.2"
                    ),

                "/js/mage/translate_inline.js"=> array(
                    "13775c527cd39bced651050d072b0021"=> "CE 1.0", 
                    "219437ece6900633563e3cdee1f9d147"=> "CE 1.6.0.0", 
                    "55941704b38897be5673d3dca46bd78d"=> "CE 1.9.3.2", 
                    "5fec45f215952f4e3becd5df3655ee44"=> "EE 1.14.2.0", 
                    "653bc4fd337c63092234f0deedbfea37"=> "EE 1.14.1.0", 
                    "66cec7e9959fa77a8c472e7c251686e0"=> "EE 1.14.3.0", 
                    "69fc9a8fa89a5805f89c89e79c5b7a38"=> "EE 1.11.0.2", 
                    "6dd58e1132b1fcb09f5f20eb3c5f2e91"=> "CE 1.9.1.0", 
                    "90137353d55d43a253bea307cafa263e"=> "CE 1.1.0", 
                    "913b5412af26c3bb060b93a478beadc8"=> "CE 1.9.1.1", 
                    "bcc32eeec4a656ee3370827bfd0585b5"=> "EE 1.11.2.0"
                    ),

                "/js/prototype/validation.js" => array(
                    "1342ac8a049bb9fcd7e3c5a911822f08"=> "CE 1.0", 
                    "295494d0966637bdd03e4ec17c2f338c"=> "CE 1.4.1.0", 
                    "594c40f2438b06dcc07079786d5c38c1"=> "CE 1.4.2.0", 
                    "60943708791871a6964745805a1c60a9"=> "CE 1.1.0", 
                    "d3252becf15108532d21d45dced96d53"=> "CE 1.4.1.1"
                    ),

                "/skin/adminhtml/default/default/boxes.css" => array(
                    "05c27c288ade60aa2c4a8b02c1bddf64"=> "CE 1.9.3.2", 
                    "0902e89fb50b22d44f8242954a89300c"=> "EE 1.12.0.0", 
                    "0e8a85aee65699c9cfaed8166d2ee678"=> "CE 1.0", 
                    "1cbeca223c2e15dcaf500caa5d05b4ed"=> "CE 1.7.0.0", 
                    "29651cb812ad5a4b916d1da525df09ff"=> "CE 1.1.0", 
                    "30a39f4046f3daba55cfbe0e1ca44b4c"=> "CE 1.5.0.1", 
                    "3c92a14ac461a1314291d4ad91f1f858"=> "EE 1.13.1.0", 
                    "5b537e36cb7b2670500a384f290bcaf8"=> "CE 1.4.2.0", 
                    "61e47784d7254e531bb6ce3991d68487"=> "CE 1.9.2.x", 
                    "6aefb246b1bb817077e8fca6ae53bf2c"=> "CE 1.2.0", 
                    "6b5b0372fbeb93675bfabe24d594bd02"=> "EE 1.10.1.1", 
                    "76a565d95fa10e5449bf63549bc5c76b"=> "CE 1.3.3.0", 
                    "84b67457247969a206456565111c456b"=> "CE 1.1.x", 
                    "89c7b659d4e60aabd705af87f0014524"=> "EE 1.14.1.0", 
                    "89e986c50a1efe2e0f1a5f688ca21914"=> "EE 1.14.2.0", 
                    "8a5c088b435dbcf1bbaac9755d4ed45f"=> "EE 1.12.0.x", 
                    "a2c7f9ddda846ba76220d7bcbe85c985"=> "CE 1.2.1", 
                    "adca1795a4c58ce6a6332ceb2a6c5335"=> "CE 1.5.1.0", 
                    "b497d3538b1c18012455527f267bef53"=> "EE 1.11.0.2", 
                    "ba8dd746c8468bfd1cff5c77eadc71a4"=> "CE 1.9.x", 
                    "c89fac64edb359d899aa7ae792ec5809"=> "EE 1.14.3.0", 
                    "d0511b190cdddf865cca7873917f9a69"=> "CE 1.1.1", 
                    "dd6fbd6cc6376045b3a62a823af9a361"=> "EE 1.10.0.2", 
                    "e895117cde7ba3305769bc1317a47818"=> "EE 1.11.2.0"
                    )


                );
}



}
