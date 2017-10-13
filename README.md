## About This Project

Help business owner generate a report for the website.


## 数据库设置相关
- 修改根目录.env加入相关的mysql信息，我们这里用的数据库名是：mageaudit
- 创建数据库： create database mageaudit
- 执行migration操作，进入项目根目录，运行： php artisan migrate
- 数据库调用简单例子 [url]/security/testdb, [file] /app/Http/Controllers/SecurityController.php


## SEMRUSH 相关信息
需要把api_key放到/.env文件里面，我已经创建了一个controller入口在 /app/Http/Controllers/SemController.php，请修改process方法加入相关逻辑。


- 保存数据url: /security/savedata [post]domain=test.com&email=test@sample.com
- 生产pdf url: /security/createreport [post]html=<html ...