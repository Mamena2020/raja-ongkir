# Raja Ongkir
package to get cost delivery from raja ongkir api


# how to use it?
```
RajaOngkirService::$apiKey="api key raja ongkir";
```

   - get province
```
RajaOngkirService::Province();
response:

[
  {
    "province_id": "1",
    "province": "Bali"
  },
  {
    "province_id": "2",
    "province": "Bangka Belitung"
  },
  {
    "province_id": "3",
    "province": "Banten"
  },
  {
    "province_id": "4",
    "province": "Bengkulu"
  },
  ...
]
```   
   
  - get province by id
```
RajaOngkirService::ProvinceByID(1);
response:   

{
  "province_id": "1",
  "province": "Bali"
}
```

 - get all city in indonesia
```
RajaOngkirService::Cities();
response:

[
  {
    "city_id": "1",
    "province_id": "21",
    "province": "Nanggroe Aceh Darussalam (NAD)",
    "type": "Kabupaten",
    "city_name": "Aceh Barat",
    "postal_code": "23681"
  },
  {
    "city_id": "2",
    "province_id": "21",
    "province": "Nanggroe Aceh Darussalam (NAD)",
    "type": "Kabupaten",
    "city_name": "Aceh Barat Daya",
    "postal_code": "23764"
  },
  ...
]  

```

   - get city of province
```
RajaOngkirService::CityOfProvince(1);
response:
[
  {
    "city_id": "17",
    "province_id": "1",
    "province": "Bali",
    "type": "Kabupaten",
    "city_name": "Badung",
    "postal_code": "80351"
  },
  {
    "city_id": "32",
    "province_id": "1",
    "province": "Bali",
    "type": "Kabupaten",
    "city_name": "Bangli",
    "postal_code": "80619"
  },
  ...
]

```

 - get city by id
```
RajaOngkirService::CityByID(284);
response:
{
  "city_id": "284",
  "province_id": "24",
  "province": "Papua",
  "type": "Kabupaten",
  "city_name": "Mimika",
  "postal_code": "99962"
}

```

   - get cost
```
RajaOngkirService::Cost(20, 200, Courier::jne,284);
/* params: 
   destination=20 (city id)
   weight=200 gr
   courier = jne
   origin city id = 284
*/   
response:
[
  {
    "code": "jne",
    "name": "Jalur Nugraha Ekakurir (JNE)",
    "costs": [
      {
        "service": "OKE",
        "description": "Ongkos Kirim Ekonomis",
        "cost": {
          "value": 72000,
          "etd": "3-4",
          "note": ""
        }
      },
      {
        "service": "REG",
        "description": "Layanan Reguler",
        "cost": {
          "value": 80000,
          "etd": "2-3",
          "note": ""
        }
      }
    ]
  }
]
```
