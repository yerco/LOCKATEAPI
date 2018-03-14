# README.md

## Lockate API

## dev setup
- PHP 7.1.7
- Symfony 3.1.4
- FosUserBundle 2.0
- mysql  Ver 14.14 Distrib 5.7.20, for osx10.13 (x86_64) using EditLine wrapper
- Server: Apache/2.4.28 (Unix) PHP/7.1.7 (obtained using `curl -v localhost`)
    - `.htaccess` `Header unset "X-Powered-By"` to eliminate the header `"X-Powered-By"`
    - modifying with the directives below **/etc/apache2/httpd.conf** only `Server: Apache` appears
    ```
    ServerSignature Off
    ServerTokens Prod
    ```
    - Last change needs server restart
        
### Testing

After downloading **phpunit-6.5.phar**
```
$ ./phpunit-6.5.phar 
```
```
$ ./phpunit-6.5.phar tests/LockateAPIBundle/Controller/ApiControllerTest.php 
```
```
$ ./phpunit-6.5.phar tests/LockateAPIBundle/Service/PersistSensedDataTest.php
```
```
$ ./phpunit-6.5.phar tests/LockateAPIBundle/Controller/TokenControllerTest.php 
```
```
$ ./phpunit-6.5.phar --filter testSensedDataEndpointUsingGuzzle
```



### Examples using `curl`
```
$ curl -H "Content-Type: application/json" -X POST \
-d '{"node_id": "xyz", "timestamp": "xyz", "var1": "bloodyroots"}' \ 
http://localhost/api/v1/sensors
```
```
$ curl -v -H "Content-Type: application/json" -X POST \
-d '{"gateway_record": [{"gateway_id": 0,"timestamp": "1519912976000", \
"node_record": [{"node_id": 27, "timestamp":"1519912976000","di": {"di_1": "on", "di_2": "off"}, \
"ai": {"ai": 25.74}}]}]}'  http://localhost/api/v1/senseddata  -H "X-AUTH-TOKEN: schmier"
```

Token request
```
$ curl -v -H "Content-Type: application/json" -X POST -u uno:uno http://localhost/api/v1/tokens -H "X-AUTH-TOKEN: schmier"
```

Sensor data post
```
curl -v -H "Content-Type: application/json" -X POST -d '{"gateway_record":[{"gateway_id": 0,"timestamp": "1521025377","node_record": [{"node_id": 27,"timestamp": "1521025377","ai": {"rssi": -86.0},"txt": {"mac": "90:e7:c4:xx:xx:xx", "company": "HTC Corporation"}}]}]}' -H "Authorization: Bearer eyJhbGciOiJSUzI1NiJ9.eyJ1c2VybmFtZSI6InVubyIsImV4cCI6MTUyMTAyODYyMCwiaWF0IjoxNTIxMDI1MDIwfQ.apYJESLQkxfdQr_XS6MWNPGnIAVR5gf0ufoe_ficgAUTXTlHv4sQbwnls28Ph2rIn0PiDVvaIGyeBBfAfT4cF2aS_2Qh26qsG0K8HKoFf0aoiCiQ6H9tI-2QhcvM6qt2GfblT7xQmR0rPfV5josYb0c7U8ra1Yery44duELdz-J345qSBfpTB2ah4LQOGpnem11z9yhFefX3YAn7UTlCV_gO7zX4DvwpzI1Sb4DhQaynqqwxwmCpJhC5OnVbnJqEM3d43Xe6IRdAUySYJ3HzZ3LJkFIu1B_JCYc_0I2SKQkm8he0Xjf0e-0glCnJLi8r8F6YcsdERiSs4LpmD53oIY1aUZoLzAWYPIibyHwFXeSTSGz7o5rnYuC75yQswdUvSD_yi_ltk0y3ukGFJGU1cTF6V3LNQkTexWOl5dGwalizFDAecFhibVaqIBfSq7eGb0g__h-WheJqTz6WcElnuJda9WmbRo-eUtGOltbI1i11a0SkzN7bwwula_SP6hWnySMTQQ4q7Y2PS0ZAcKPvLpCMoDW9mZudFQjxKIDWM8TsoQWhX3XXP9LkBfpFrST9PBDjLusY3DaJ-QyP6OiTKESzE5RzUZMS4-HR4_Jd2UMvT4tmeWEkWvY0aI7SeNbxUR1z1_TiIuojwROzJ7fLunWG_df8Rogsaelnx4Be-og" http://localhost/api/v1/senseddata
```

## Shortcuts
```
sudo rm -rf var/cache/* && sudo sudo rm -rf var/logs/*
```

