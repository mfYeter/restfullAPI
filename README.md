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
- Herhangi bir id değeri verilmediği veya veritabanında eşleşen bir değer bulamağında tüm veriler listelenir. [Örnek veri giriş ve çıkışı](/json_examples/customer_json_get_example_input.json)
### api_customers.php / POST için 
- 






