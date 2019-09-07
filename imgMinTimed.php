<?php
error_reporting(1);
require_once ('DB.php');

require(__DIR__ . '/php-curl-class/src/Curl/MultiCurl.php');
require(__DIR__ . '/php-curl-class/src/Curl/CaseInsensitiveArray.php');
require(__DIR__ . '/php-curl-class/src/Curl/Curl.php');
require(__DIR__ . '/php-curl-class/src/Curl/Decoder.php');
require(__DIR__ . '/php-curl-class/src/Curl/ArrayUtil.php');
use \Curl\MultiCurl;

$imgFolder =  __DIR__ . "/img/";
$xlsFolder =  __DIR__ . "/xls/";

$sourceOut = scandir($imgFolder);
$sourceXls = scandir($xlsFolder);

$url = "https://www.iaai.com/Images/EnlargeImages?stockNumber=";

$url1 = "https://vis.iaai.com/resizer?imageKeys=";
$url2 = "~SID~I";
$url3 = "&width=640&height=480";
$urls = [];

for ($j = 0; $j < count($sourceXls); $j++){
    if($sourceXls[$j] != "." && $sourceXls[$j] != ".."){
        $data = [];
        $elData = [];

        $matches = file_get_contents($xlsFolder . $sourceXls[$j]);
        $tmp = explode("\n", $matches);
        for($i= 0; $i < count($tmp); $i++){
            //            echo $tmp[$i] . "\n";
            $elementData = trim(explode(":", $tmp[$i])[1]);
//            var_dump($elData);
            if($elementData != ""){
                array_push($elData, $elementData);
            }
        }
//        var_dump($elData);
        for($i= 0; $i < count($elData); $i++){
            switch($i){
                case 0:
                    array_push($data, $elData[$i]);
                    break;
                case 1:
                    array_push($data, $elData[$i]);
                    break;
                case 2:
                    $sql14 = "SELECT `make_id`, `type` FROM `make` WHERE `make` = :make";
                    $stmt14 = $pdo->prepare($sql14);
                    $stmt14->execute([':make' => $elData[$i]]);
                    $res14 = $stmt14->fetchAll(PDO::FETCH_ASSOC);
                    $res14 = $res14[0]["make_id"];
                    array_push($data, $res14);
                    break;
                case 3:
                    $sql14M = "SELECT `model_id` FROM `model` WHERE `make_id` = :make_id AND `model` = :model";
                    $stmt14M = $pdo->prepare($sql14M);
                    $stmt14M->execute([':make_id' => $data[2], ':model' =>$elData[$i]]);
                    $res14M = $stmt14M->fetch(PDO::FETCH_NUM);
                    $res14M = $res14M[0];
                    array_push($data, $res14M);
                    break;
                case 4:
                    $sql6 = "SELECT `year_id` FROM `year` WHERE `year` = :year";
                    $stmt6 = $pdo->prepare($sql6);
                    $stmt6->execute([':year' => $elData[$i]]);
                    $res6 = $stmt6->fetch(PDO::FETCH_NUM);
                    $res6 = $res6[0];
                    array_push($data, $res6);
                    break;
                case 5:
                    array_push($data, $elData[$i]);
                    break;
                case 6:
                    array_push($data, 4);
                    break;
                case 7:
                    array_push($data, $elData[$i]);
                    break;
                case 8:
                    array_push($data, $elData[$i]);
                    break;
                case 9:
                    $elDates= substr($elData[$i], 2);
                    $elDate = explode("/", $elDates);
                    $elDateRez = $elDate[0] . $elDate[1] . $elDate[2];
//                    echo  $elDateRez . "\n";
                    $elDateRez = (int)$elDateRez;

                    $sql0= "SELECT `date_id` FROM `dates` WHERE `date` = :date";
                    $stmt0 = $pdo-> prepare($sql0);
                    $stmt0-> execute([':date'=>$elDateRez]);
                    $resDate = $stmt0->fetch(PDO::FETCH_NUM);
                    $resDate = $resDate[0];
                    array_push($data, $resDate);
                    break;
                case 10:
//                    echo  $elData[$i] . "\n";
                    $elOdo = $elData[$i];
                    array_push($data, $elOdo);
                    break;
                case 11:
                    if($elData[$i] == "yes"){
                        $apprEl = 1;
                    }else{
                        $apprEl = 0;
                    }
                    array_push($data, $apprEl);
                    break;
                case 12:
                    $sql8 = "SELECT `engine_id` FROM `engine` WHERE `engine` = :engine";
                    $stmt8 = $pdo->prepare($sql8);

                    $stmt8->execute([':engine' => $elData[$i]]);
                    $res8 = $stmt8->fetch(PDO::FETCH_NUM);
                    $res8 = $res8[0];
                    array_push($data, $res8);
                    break;
                case 13:
                    array_push($data, $elData[$i]);
                    break;
                case 14:
                    $sql11 = "SELECT `fueltype_id` FROM `fueltype` WHERE `fueltype` LIKE ?";
                    $stmt11 = $pdo->prepare($sql11);
                    $stmt11->execute([$elData[$i]]);
                    $res11 = $stmt11->fetch(PDO::FETCH_NUM);
                    $res11 = $res11[0];
                    array_push($data, $res11);
                    break;
                case 15:
                    if($elData[$i] == "Front Wheel Drive"){
                        array_push($data, 2);
                    }elseif($elData[$i] == "Rear Wheel Drive"){
                        array_push($data, 4);
                    }elseif($elData[$i] == "All-wheel Drive"){
                        array_push($data, 3);
                    }else{
                        array_push($data, 1);
                    }
                    break;
                case 16:
                    $sql32= "SELECT `color_id` FROM `color` WHERE `color` = :color";
                    $stmt32 = $pdo-> prepare($sql32);
                    $stmt32-> execute([':color'=>$elData[$i]]);
                    $resColor = $stmt32->fetch(PDO::FETCH_NUM);
                    $elem = $resColor[0];
                    array_push($data, $elem);
                    break;
                case 17:
                    if($elData[$i] == "Collision"){
                        array_push($data, 2);
                    }elseif($elData[$i] == "Other"){
                        array_push($data, 3);
                    }elseif($elData[$i] == "Water"){
                        array_push($data, 4);
                    }elseif($elData[$i] == "Theft"){
                        array_push($data, 5);
                    }elseif($elData[$i] == "Fire"){
                        array_push($data, 6);
                    }else{
                        array_push($data, 1);
                    }
                    break;
                case 18:
                    $sql9 = "SELECT `damage_id` FROM `damage` WHERE `damage` = :damage";
                    $stmt9 = $pdo->prepare($sql9);
                    $stmt9->execute([':damage' => $elData[$i]]);
                    $res9 = $stmt9->fetch(PDO::FETCH_NUM);
                    $res9 = $res9[0];
                    $elem = (int)$res9;
                    array_push($data, $elem);
                    break;
                case 19:
                    if($elData[$i] == "yes"){
                        array_push($data, 1);
                    }else{
                        array_push($data, 0);
                    }
                    break;
                case 20:
                    if($elData[$i] == "Car Starts"){
                        array_push($data, 2);
                    }elseif($elData[$i] == "Cant Test"){
                        array_push($data, 7);
                    }elseif($elData[$i] == "Didnt Test"){
                        array_push($data, 5);
                    }elseif($elData[$i] == "Engine Damage"){
                        array_push($data, 6);
                    }elseif($elData[$i] == "Starts WJump"){
                        array_push($data, 3);
                    }elseif($elData[$i] == "Wont Start"){
                        array_push($data, 4);
                    }else{
                        array_push($data, 1);
                    }
                    break;
                case 21:
                    if($elData[$i] == "yes"){
                        array_push($data, 1);
                    }elseif($elData[$i] == "no"){
                        array_push($data, 2);
                    }else{
                        array_push($data, 0);
                    }
                    break;
                case 22:
                    $elBranches = explode("(", $elData[$i]);
                    $elBranch =  trim($elBranches[0]) . ", " . trim(substr($elBranches[1], 0, -1));
                    echo $elBranch . "\n";
                    $sql22= "SELECT `branchname_id` FROM `branchname` WHERE `branchname` LIKE ?";
                    $stmt22 = $pdo-> prepare($sql22);
                    $stmt22-> execute(["%" . $elBranch . "%"]);
                    $res22 = $stmt22->fetch(PDO::FETCH_NUM);
                    $res22 = $res22[0];
                    array_push($data, $res22);
                    break;
                case 23:
                    $elDockes = explode("-", $elData[$i]);
                    $elDock =  trim($elDockes[0]) . "  - " . explode(" ", trim($elDockes[1]))[0];
                    echo $elDock . "\n";
                    $sql5= "SELECT `saledoc_id` FROM `saledoc` WHERE `saledoc` LIKE ?";
                    $stmt5 = $pdo-> prepare($sql5);
                    $stmt5-> execute(["%" . $elDock . "%"]);
                    $res5 = $stmt5->fetch(PDO::FETCH_NUM);
                    $res5 = $res5[0];
                    $elem = (int)$res5;
                    array_push($data, $elem);
                    break;
            }
        }
//        var_dump($data);
        echo "INSERT INTO `autoastatFix`.`lotsNorm` (`lot_id`, `date`, `winamt`, `winamtAct`, `odostatus`, `estrepairs`, `vin`, `saledoc`, `year`, `cylinders`, `engine`, `damage`, `odometer`, `fueltype`, `drivelinetype`, `publicflag`, `make`, `model`, `loss`, `field48`, `bidclass`, `starts`, `startsflag`, `acv`, `branchname`, `rundriveflag`, `active`, `auction`, `auctLotNo`, `saleStatus`, `approved`, `minMet`, `modelDet`, `keyEx`, `imgDet`, `color`, `buyNowPr`, `curBid`, `winnBiderNo`, `country`, `zipIndex`, `imgJson`) VALUES (NULL, '" . $data[9] . "', '" . $data[5] . "', '0', '" . $data[11] . "', '" . $data[8] . "', '" . $data[1] . "', '" . $data[23] . "', '" . $data[4] . "', '" . $data[13] . "', '" . $data[12] . "', '" . $data[18] . "', '" . $data[10] . "', '" . $data[14] . "', '" . $data[15] . "', '0', '" . $data[2] . "', '" . $data[3] . "', '" . $data[17] . "', '1', '3', '" . $data[20] . "', '1', '" . $data[7] . "', '" . $data[22] . "', '" . $data[19] . "', '1', '1', '" . $data[0] . "', '4', '0', '0', '0', '" . $data[21] . "', '" . $data[9] . "', '" . $data[16] . "', '0', '0', '0', '1', '0', '0');\n";

//Get images
        $multi_curl_first = new MultiCurl();
        $multi_curl_first->setConcurrency(10);
        $lotNoParseNo = [];

        $multi_curl_first->success(function ($instance){
            global $data, $url, $lotNoParseNo;

//            preg_match_all("/=.*/",$instance->baseUrl, $resultNum);
//            $instanceNum = (int)substr($resultNum[0][0], 1);
////    var_dump($instanceNum);
//            echo $instanceNum . " - instanceNum \n";

            preg_match_all("/K\":\"[0-9]*/",$instance->response, $result);
//    $rezStr = $result[0][0];
            $rez_parse = (int)substr($result[0][0], 4);
//            var_dump($rez_parse);
            $lotNoParseNo += [$rez_parse=>$data[0]];
        });
        $multi_curl_first->error(function ($instance){
        });
        $multi_curl_first->complete(function ($instance){
        });

        $urlGet = $url . $data[0];
//        echo $urlGet . "\n";
        $res_first = $multi_curl_first->addGet($urlGet);

// Blocks until all items in the queue have been processed.
        $multi_curl_first->start();
        $multi_curl_first->close();
        unset($multi_curl_first);

//        echo " lotNoParseNo \n";
//        var_dump($lotNoParseNo);

        $multi_curl = new MultiCurl();
        $multi_curl->setConcurrency(10);

        $multi_curl->success(function ($instance){
            global $searchOut, $data, $imgFolder, $lotNoParseNo, $elData;

            $imgNumServ = explode("~", explode("=", $instance->url)[1])[0];
            $imgNum = (int)substr(explode("&", explode("~", $instance->url)[2])[0], 1);
//    echo $imgNum ." - imgNum \n";

            $lotNum = $data[0];
            $vinNum = $data[1];
            $elDates= substr($elData[9], 2);
            $elDate = explode("/", $elDates);
            $dateNum = $elDate[0] . $elDate[1] . $elDate[2];
//    echo $imgFolder . $dateNum . "/" . $vinNum . "/" . $vinNum . "_" . $imgNum . ".jpg" . " - source \n";

            if(!file_exists($imgFolder . $dateNum )){
                mkdir($imgFolder . $dateNum);
            }
            if(!file_exists($imgFolder . $dateNum . "/" . $vinNum . "/" . $vinNum . "_" . $imgNum . ".jpg")){
                if(!file_exists($imgFolder . $dateNum . "/" . $vinNum)){
                    mkdir($imgFolder . $dateNum . "/" . $vinNum);
//            echo $imgFolder . $date . "/" . $vin . "\n";
                }
                file_put_contents($imgFolder . $dateNum . "/" . $vinNum . "/" . $vinNum . "_" . $imgNum . ".jpg", $instance->response);
//                echo $imgFolder . $dateNum . "/" . $vinNum . "/" . $imgNum . "\n";
            }
        });
        $multi_curl->error(function ($instance){
        });
        $multi_curl->complete(function ($instance){
        });

//        foreach ($urls as $url){
////    echo $url . "\n";
//            $res = $multi_curl->addGet($url);
//        }

        foreach($lotNoParseNo as $key => $value){
            for($i = 1; $i < 11; $i++){
                $urlGet = $url1 . $key . $url2 . $i . $url3;
//                echo $urlGet . "\n";
                $res_first = $multi_curl->addGet($urlGet);
            }
        };

// Blocks until all items in the queue have been processed.
        $multi_curl->start();
        $multi_curl->close();
        unset($multi_curl);
        gc_collect_cycles();

        ini_set('gd.jpeg_ignore_warning', true);

        $elDates= substr($elData[9], 2);
        $elDate = explode("/", $elDates);
        $dateNum = $elDate[0] . $elDate[1] . $elDate[2];
        $vinNum = $data[1];

        $source = scandir($imgFolder . "/" . $dateNum . "/" . $vinNum);

//        var_dump($source);
        for($i = 0; $i < count($source); $i++){
            $path = $imgFolder . $dateNum . "/" . $vinNum . '/' . $source[$i];
//            echo $path . " - path \n";
            if(is_file($path)){
                //Crop bottom information
                if(getimagesize($path)[0] > 241){
                    $image = imagecreatefromjpeg($path);
                    //works only for width > 640
                    $image1 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 640, 'height' => 460]);
                    imagejpeg($image1, $path);
//                    echo $path . "_" . $i . " path \n";

                    $image = imagecreatefromjpeg($path);
                    $imageScaled = imagescale($image, 240);
                    // Save
                    imagejpeg($imageScaled, $path);

                    // Free up memory
                    imagedestroy($image);
                }
            }
        }
        
        rename($xlsFolder . $sourceXls[$j], __DIR__ . "/txtArchive/" . $sourceXls[$j]);
    }
}
