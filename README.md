# RESTFULL API

Basit bir RESTful API Uygulaması

## Teknolojiler

PHP ve Mysql

## Dosya Dizini

```shell
ideasoft_case/
├───── config/
│  ├───────── connect.php
│  ├───────── function.php
│  ├───────── features
├── core_api/
│   ├───────── api_customers.php
│   ├───────── api_orders.php
│   ├───────── api_products.php
├───── .htaccess 
├───── client.php
├───── ideasoft_case.sql 
└── ...
```

## Proje Hakkında

Teknik yeterlilik konusunun anlaşılması için geliştirilmiş olup,  eksiklikler ve tamamlananlar aşağıdaki gibidir.

## Eksik Görevler
### api_orders.php için
- Put ve Delete işlemleri yapılmamıştır.
- 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında en ucuz ürüne %20 indirim yapmayıp, genel toplamdan yapmaktadır.

## Tamamlananlar
- .htaccess link yapıları düzenlenmiştir.
### api_customers.php için
- GET, POST, PUT ve DELETE methodları tamamlanmıştır.
- Veri girişi kuralları tanımlanmıştır.
### api_products.php için
- GET, POST, PUT ve DELETE methodları tamamlanmıştır.
- Veri girişi kuralları tanımlanmıştır.
### api_orders.php için
- GET ve POST methodları tamamlanmıştır.
- Stok Kontrolü yapılmıştır ve istenmeyen durumda veritabanı kayıt işlemlerini kontrol etmek için rollback ve commit yapısı kullanılmıştır.
- Veri giriş kuralları tanımlanmıştır.
- İndrim kuralları tanımlanmıştır.
- Sipariş girişi sonrası response.json bildirimi tamamlanmıştır.
## Kurallar
### api_customers.php / GET için
- link yapısı http://localhost/api/v1/customers dır.
- Herhangi bir id değeri verilmediği veya veritabanında eşleşen bir değer bulamağında tüm veriler listelenir.
### Örnek Giriş -1
```shell
[
	{
	}
	
]
```
### Örnek Çıkış -1
```shell
	[
		{
			"id": 24,
			"CustomerName": "test",
			"Since": "0000-00-00",
			"Revenue": "0.00"
		},
		{
			"id": 25,
			"CustomerName": "test2",
			"Since": "2022-10-01",
			"Revenue": "0.10"
		}
	]
```
### api_customers.php / POST için 
- link yapısı http://localhost/api/v1/customers dır.
- veri giriş kontrolü yapılmış ve hatalı giriş yapıldığı taktirde response.json olarak veri girişi hakkında geri bildirim vermektedir. 
### Örnek giriş ve çıkışları aşağıda paylaşılmıştır.
```shell
[
	{
		"Müşteri Adi":"Örnek Şirket"
	}
]
```
```shell
{
	"Status": "Error",
	"Code": 406,
	"Message": "Method Not Allowed, Müşteri Adi. Array is not Acceptable data format ",
	"Needed Keys": {
		"CustomerName": "",
		"Since": "",
		"Revenue": ""
	}
}
```
```shell
[
	{
		"id":1,
		"CustomerName": "",
		"Since": "",
		"Revenue": ""
	}
]
```
```shell
{
	"Status": "Error",
	"Code": 406,
	"Message": "Not Acceptable, 0. Array Invalid data -> id"
}

```
```shell
[
	{
		"CustomerName": "",
		"Since": "",
		"Revenue": ""
	}
]

```
```shell
{
	"Status": "Error",
	"Code": 406,
	"Message": "Not Acceptable, 0. Array Customer name is null"
}
```
```shell
[
	{
		"CustomerName": "test",
		"Since": "99-99-99",
		"Revenue": ""
	}
]
```
```shell
{
	"Status": "Error",
	"Code": 406,
	"Message": "Not Acceptable, 0. Array Date is not Acceptable format <> Y-d-m"
}
```
```shell
[
	{
		"CustomerName": "test",
		"Since": "2022-12-12",
		"Revenue": "100.00"
	}
]
```
```shell
{
	"Status": "OK",
	"Code": 201,
	"Message": "Data is Created."
}
```




