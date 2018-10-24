# Laravel shop 商城

用 PHP 框架 Laravel 5.7 開發的簡易商城，前端介面採用 Bootstrap 4。

功能包含：
用戶登入、瀏覽商品、將商品加入購物車、結帳、評分、退款、(未加入優惠卷)
後台用戶管理、商品管理、商品管理、訂單管理。

前台帳號均由亂數產生。後台帳號：admin/密碼：secret。

![Laravel shop 商城 首页](https://yangchenshin77.github.io/Laravel-shop/example-1.jpg)
![Laravel shop 商城 商品页](https://yangchenshin77.github.io/Laravel-shop/example-2.jpg)
![Laravel shop 商城 后台](https://yangchenshin77.github.io/Laravel-shop/example-3.jpg)

## 安裝

下載源碼：

```
git clone git@github.com:yangchenshin77/Laravel-shop.git
```

配置 homestead.yaml，加入對應修改

```
folders:
    - map: ~/my-path/laravel-shop/ # 你本地的項目路徑
      to: /home/vagrant/code/laravel-shop

sites:
    - map: laravel-shop.test
      to: /home/vagrant/code/laravel-shop/public

databases:
    - laravel-shop
```

安裝擴展包依賴

```
composer install
```

生成金鑰

```
php artisan key:generate
```

資料庫遷移

```
php artisan migrate
```

生成上傳文件資料夾連結

```
php artisan storage:link
```

導入後台帳號資料 SQL

```
mysql laravel-shop < database/admin.sql
```

前端擴展包

```
npm install
```
或
```
yarn install
```

## 配置 Supervisor

安装 Supervisor (Homestead 默認已安裝，Linux 可用，Windows 不可用，需執行 `php artisan queue:work redis`)

```
sudo apt-get install supervisor
```

開啟 laravel-shop-worker.conf，將 `/home/vagrant/code/laravel-shop/` 你替換成項目的路徑，`user=vagrant` 改成你的用戶名。

執行

```
sudo cp laravel-shop-worker.conf /etc/supervisor/conf.d/laravel-shop-worker.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-shop-worker:*
```

停止

```
sudo supervisorctl stop laravel-shop-worker:*
```
