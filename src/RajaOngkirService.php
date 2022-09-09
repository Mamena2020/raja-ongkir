<?php

namespace Mamenatech\RajaOngkir;

use Exception;
use stdClass;

enum Courier
{
    case jne;
    case tiki;
    case pos;
}

class RajaOngkirService
{



    // // private const OriginCityID = 284;// MImika
    // public const OriginCityID = 115;


    public static $apiKey;


    public static function Couriers(): array
    {
        return [
            Courier::jne->name,
            Courier::tiki->name,
            Courier::pos->name,
        ];
    }

    public static function CourierByName($name): Courier
    {
        if ($name == Courier::jne->name) {
            return Courier::jne;
        }
        if ($name == Courier::tiki->name) {
            return Courier::tiki;
        }
        if ($name == Courier::pos->name) {
            return Courier::pos;
        }
        return null;
    }


    private static function Call($url, $method = "GET", $body = "")
    {
        $curl = curl_init();

        $request = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                "key: " . self::$apiKey
            ),
        );
        if (strtoupper($method) == "POST") {
            $request = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: " . self::$apiKey
                ),
            );
        }

        curl_setopt_array($curl, $request);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            return null;
        } else {
            return $response;
        }
    }




    private static function Process($result)
    {

        // echo "'data type2 - ".gettype($result);
        $obj = json_decode($result, true);
        // echo "'data type2 - ".gettype($obj);
        // dd($obj["rajaongkir"]);
        if ($obj["rajaongkir"]["status"]["code"] == 200) {
            return $obj["rajaongkir"]["results"];
        }
        return null;
    }



    //----------------------------------------------------------------------------------------------------- Province
    // all province in indonesia
    public static function Province(): array
    {
        $url = "http://api.rajaongkir.com/starter/province";
        $result = self::Call($url);
        if ($result) {
            $data = self::Process($result);
            if (count($data) > 0) {
                $newList = [];
                foreach ($data as $item) {
                    $obj = new stdClass();
                    $obj->province_id =  $item["province_id"];
                    $obj->province =  $item["province"];
                    array_push($newList, $obj);
                }
                return $newList;
            }
        }
        return [];
    }
    // privince by id
    public static function ProvinceByID($id = 0): object
    {
        $url = "http://api.rajaongkir.com/starter/province?id=" . $id;
        $result = self::Call($url);
        if ($result) {
            $data = self::Process($result);
            $obj = new stdClass();
            $obj->province_id =  $data["province_id"];
            $obj->province =  $data["province"];
            return $obj;
        }
        return null;
    }
    //----------------------------------------------------------------------------------------------------- city

    // all city in indonesia
    public static function Cities(): array
    {
        $url = "http://api.rajaongkir.com/starter/city";
        $result = self::Call($url);
        if ($result) {
            $data = self::Process($result);
            if (count($data) > 0) {
                $newList = [];
                foreach ($data as $item) {
                    $obj = new stdClass();
                    $obj->city_id =  $item["city_id"];
                    $obj->province_id =  $item["province_id"];
                    $obj->province =  $item["province"];
                    $obj->type =  $item["type"];
                    $obj->city_name =  $item["city_name"];
                    $obj->postal_code =  $item["postal_code"];
                    array_push($newList, $obj);
                }
                return $newList;
            }
        }
        return [];
    }
    // city by province id
    public static function CityOfProvince($province_id = 0): array
    {
        $url = "http://api.rajaongkir.com/starter/city?province=" . $province_id;
        $result = self::Call($url);
        if ($result) {
            $data = self::Process($result);
            if (count($data) > 0) {
                $newList = [];
                foreach ($data as $item) {
                    $obj = new stdClass();
                    $obj->city_id =  $item["city_id"];
                    $obj->province_id =  $item["province_id"];
                    $obj->province =  $item["province"];
                    $obj->type =  $item["type"];
                    $obj->city_name =  $item["city_name"];
                    $obj->postal_code =  $item["postal_code"];
                    array_push($newList, $obj);
                }
                return $newList;
            }
        }
        return [];
    }
    // city by id
    public static function CityByID($id = 0): object
    {
        $url = "http://api.rajaongkir.com/starter/city?id=" . $id;
        $result = self::Call($url);
        if ($result) {

            $data = self::Process($result);
            if ($data) {
                $obj = new stdClass();
                $obj->city_id =  $data["city_id"];
                $obj->province_id =  $data["province_id"];
                $obj->province =  $data["province"];
                $obj->type =  $data["type"];
                $obj->city_name =  $data["city_name"];
                $obj->postal_code =  $data["postal_code"];
                return $obj;
            }
        }
        return null;
    }
    //----------------------------------------------------------------------------------------------------- cost
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $weight : in gram
     * @param int $destination : city id
     * @param Courier $courier :  jne, pos, tiki.
     * @return array
     */
    public static function Cost(
        int $destination = 0,
        int $weight = 0,
        Courier $courier = Courier::jne,
        int $originCityID
    ): array {

        try {
            if ($originCityID == null) {
                throw 'city id cannot be null';
            }

            $url = "http://api.rajaongkir.com/starter/cost";
            $body = "origin=" . $originCityID . "&destination=" . $destination . "&weight=" . $weight . "&courier=" . $courier->name;
            // echo $body;
            $result = self::Call($url, "POST", $body);

            if ($result) {
                $data = self::Process($result);
                if ($data != null && count($data) > 0) {
                    $newList = [];
                    foreach ($data as $item) {
                        $obj = new stdClass();
                        $obj->code =  $item["code"];
                        $obj->name =  $item["name"];
                        $obj->costs = [];
                        foreach ($item["costs"] as $cost) {
                            $objCost = new stdClass();
                            $objCost->service = $cost["service"];
                            $objCost->description = $cost["description"];
                            $objCost2 = new stdClass();
                            $objCost2->value = $cost["cost"][0]["value"];
                            $objCost2->etd = $cost["cost"][0]["etd"];
                            $objCost2->note = $cost["cost"][0]["note"];
                            $objCost->cost = $objCost2;
                            array_push($obj->costs, $objCost);
                        }
                        array_push($newList, $obj);
                    }
                    // echo "new list\n";
                    return $newList;
                }
            }
        } catch (Exception $e) {
            echo $e;
        }
        return [];
    }

    public static function test()
    {
        return self::Cost(20, 200, Courier::jne,284);
    }

    //-----------------------------------------------------------------------------------------------------
}
