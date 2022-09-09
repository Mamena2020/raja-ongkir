# raja-ongkir
package to get cost delivery from raja ongkir api


how to use it?

RajaOngkirService::$apiKey="api key raja ongkir";
RajaOngkirService::Cost(20, 200, Courier::jne,284);

resonse:
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
