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
```
$ curl -v -H "Content-Type: application/json" -X POST \
-d '
```

## Shortcuts
```
sudo rm -rf var/cache/* && sudo sudo rm -rf var/logs/*
```

