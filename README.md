# RESTFULL API

RESTful API Uygulaması

## Teknolojiler

PHP ve Mysql

## Dosya Dizini

```shell
ideasoft_case/
├───── config/
│  ├───────── connect.php
│  ├───────── function.php
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
- Link yapısı http://localhost/api/v1/customers dır.
- Veri giriş kontrolü yapılmış ve hatalı giriş yapıldığı taktirde response.json olarak veri girişi hakkında geri bildirim vermektedir ve id değeri kabul edilmemektedir.
- Hatalar, kaçıncı dizide ise dizi bilgisi verilmektedir. 
- Veri girişi doğru kabul edildiği taktirde veritabanı kayıtı yapılarak response.json olarak geri bildirim verilmektedir.
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
### api_customers.php / PUT için 
- Link yapısı http://localhost/api/v1/customers dır.
- Veri giriş kontrolü yapılmış ve hatalı giriş yapıldığı taktirde response.json olarak veri girişi hakkında geri bildirim vermektedir.
### Örnek giriş ve çıkışları aşağıda paylaşılmıştır.
```shell
[
	{
		"id":1,
		"Kontrol": "test",
		"Since": "2022-12-12",
		"Revenue": "0.00"
	}		
]
```
```shell
{
	"Status": "Error",
	"Code": 406,
	"Message": "Not Acceptable, 0. in Array some key is not found.",
	"Need Keys": [
		{
			"CustomerName": ""
		}
	]
}
```
```shell
[
	{
		"id":"gh",
		"CustomerName": "test",
		"Since": "2022-12-12",
		"Revenue": "0.00"
	}		
]
```
```shell
{
	"Status": "Error",
	"Code": 406,
	"Message": "Not Acceptable, 0. Array id is not numeric."
}
```
```shell
[
	{
		"id":"1",
		"CustomerName": "tes3t",
		"Since": "2022-12-13",
		"Revenue": "0.00"
	}		
]
```
```shell
{
	"Status": "OK",
	"Code": 201,
	"Message": "Data is updated."
}
```
### api_customers.php / DELETE için
- Link yapısı http://localhost/api/v1/customers dır.
- Veri giriş kontrolü yapılmış ve hatalı giriş yapıldığı taktirde response.json olarak veri girişi hakkında geri bildirim vermektedir ve sadece ID değeri kabul edilmektedir. 
### Örnek giriş ve çıkışları aşağıda paylaşılmıştır.
```shell
[
	{
		"id":"1",
		"CustomerName": "test"
	}		
]
```
```shell
{
	"Status": "Error",
	"Code": 406,
	"Message": "Not Acceptable, 0. Please add id information only."
}
```
```shell
[
	{
		"id":"1",
	}		
]
```
```shell
{
	"Status": "Ok",
	"Code": 200,
	"Message": "Deleted."
}
```
### api_product.php / GET için 
- link yapısı http://localhost/api/v1/products dır.
- Herhangi bir id değeri verilmediği veya veritabanında eşleşen bir değer bulamağında tüm veriler listelenir.
- ID değerinden farklı bir değer kabul edilmemektedir. Örnek veri giriş ve çıkışları yukarıdaki gibidir.
### api_product.php / POST için 
- link yapısı http://localhost/api/v1/products dır.
- Yine yukaridaki şekilde benzer veri giriş kontrolleri yapılmaktadır. 
- Sadece ID değerine izin verilmemektedir.
### api_product.php / PUT için 
- link yapısı http://localhost/api/v1/products dır.
- Yine yukaridaki şekilde benzer veri giriş kontrolleri yapılmaktadır. 
### api_product.php / DELETE için 
- link yapısı http://localhost/api/v1/products dır.
- Yine yukaridaki şekilde benzer veri giriş kontrolleri yapılmaktadır. 
- Sadece ID değerine izin verilmemektedir.

### api_orders.php / GET için 
- link yapısı http://localhost/api/v1/orders dır.
- Herhangi bir id değeri verilmediğinde siparişleri getirmez ID alanı zorunludur.

### api_product.php / POST için 
- link yapısı http://localhost/api/v1/orders dır.
- Yine yukaridaki şekilde benzer veri giriş kontrolleri yapılmaktadır. 
- Sadece Customer ID ve items girişlerine değerine izin verilmemektedir.
- Stok kontrolü yapılmaktadır. 
- Bir hata oluştuğunda rollback devreye girmektedir. 
### Örnek giriş ve çıkışları aşağıda paylaşılmıştır.
```shell
[
	{
		"customerId":1,
		"items":
		[
		{
			"productId":"1",
			"quantity":"1"
		}
		]
	}	
]
```
```shell
{
	"Status": "OK",
	"Code": 201,
	"Message": "Data is Created.",
	"Discounts": [
		[]
	]
}
```
```shell
[
	{
		"customerId":1,
		"items":
		[
			{
			"productId":"1",
			"quantity":"500"
			}
		]
	}	
]
```
```shell
{
	"Status": "OK",
	"Code": 201,
	"Message": "Data is Created.",
	"Discounts": [
		[]
	]
}
```
```shell
[
	{
	"customerId":1,
	"items":[
		{
		"productId":"1",
		"quantity":"500"
		}
		]
	},
	{
	"customerId":2,
	"items":[
		{
		"productId":"1",
		"quantity":"500"
		}
		,{
		"productId":"3",
		"quantity":"500"
		}
		,{
		"productId":"4",
		"quantity":"500"
		}
		]
	},
		{
		"customerId":3,
		"items":[
		{
		"productId":"1",
		"quantity":"500"
		}
		]
	}	
]

```
```shell
{
	"Status": "OK",
	"Code": 201,
	"Message": "Data is Created.",
	"Discounts": [
		[
			{
				"orderId": 2,
				"discounts": [
					{
						"discountReason": "1000_over_get_%10",
						"discountAmount": "6037.50",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "18112.50",
				"discountedTotal": "42262.50"
			},
			{
				"orderId": 2,
				"discounts": [
					{
						"discountReason": "1000_over_get_%10",
						"discountAmount": "6037.50",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "18112.50",
				"discountedTotal": "42262.50"
			},
			{
				"orderId": 3,
				"discounts": [
					{
						"discountReason": "1_Category_Get_%20",
						"discountAmount": "12075.00",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "23224.50",
				"discountedTotal": "54190.50"
			},
			{
				"orderId": 3,
				"discounts": [
					{
						"discountReason": "1_Category_Get_%20",
						"discountAmount": "12075.00",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "23224.50",
				"discountedTotal": "54190.50"
			},
			{
				"orderId": 3,
				"discounts": [
					{
						"discountReason": "1_Category_Get_%20",
						"discountAmount": "12075.00",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "23224.50",
				"discountedTotal": "54190.50"
			},
			{
				"orderId": 3,
				"discounts": [
					{
						"discountReason": "1_Category_Get_%20",
						"discountAmount": "12075.00",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "23224.50",
				"discountedTotal": "54190.50"
			},
			{
				"orderId": 3,
				"discounts": [
					{
						"discountReason": "1_Category_Get_%20",
						"discountAmount": "12075.00",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "23224.50",
				"discountedTotal": "54190.50"
			},
			{
				"orderId": 3,
				"discounts": [
					{
						"discountReason": "1_Category_Get_%20",
						"discountAmount": "12075.00",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "23224.50",
				"discountedTotal": "54190.50"
			},
			{
				"orderId": 4,
				"discounts": [
					{
						"discountReason": "1000_over_get_%10",
						"discountAmount": "6037.50",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "18112.50",
				"discountedTotal": "42262.50"
			},
			{
				"orderId": 4,
				"discounts": [
					{
						"discountReason": "1000_over_get_%10",
						"discountAmount": "6037.50",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "18112.50",
				"discountedTotal": "42262.50"
			},
			{
				"orderId": 4,
				"discounts": [
					{
						"discountReason": "1000_over_get_%10",
						"discountAmount": "6037.50",
						"subtotal": "42262.50"
					}
				],
				"totalDiscount": "18112.50",
				"discountedTotal": "42262.50"
			}
		]
	]
}
```
```shell
[
	{
		"customerId":1,
		"items":[
			{
			"productId":"1",
			"quantity":"505550"
			}
			]
	}
	]
```
```shell
{
	"Status": "Error",
	"Code": 400,
	"Message": "Bad Request, items=> Key 0. 1 ProductCode  Insufficient stock."
}
```
