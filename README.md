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
curl -v -H "Content-Type: application/json" -X POST -d '{"gateway_record":[{"gateway_id": 0,"timestamp": "1521025377","node_record": [{"node_id": 27,"timestamp": "1521025377","ai": {"rssi": -86.0},"txt": {"mac": "90:e7:c4:xx:xx:xx", "company": "HTC Corporation"}}]}]}' -H "Authorization: Bearer eyJhbGciOiJSUzI1NiJ9.eyJ1c2VybmFtZSI6InVubyIsImV4cCI6MTUyMTIxNTMxNywiaWF0IjoxNTIxMjExNzE3fQ.ModXQ8UVByU2NmholbBKu9bNPCh9NA6pUd2gh4rJIPNFcDNkVMU7zPsmPPK4bpiMj7ivhL4KzhGvloRQIsE6v8y7zZzgUG3AxkrN_RTv0LAgijMtQEunPwyFTVkt8hIVuD60lPfU2TOiw52OT0BgjCDLP9jReHNejh8fbisq7fls5eox97EWbr3EXnZAK1TM3bem7qP87gFzXQGy2V-q8dZHXX8FS_hjSFtik2xPTmib66tpx8qCkf7QMOUOk9V0awlQyKdcTAmBBtKKRcgURh7d05NYkR-LBv0p_Scgzqq_lBECNs9CNTAgHVZZF6soV4r05RrX8LwyRGH6vju9Jm_SjEwl0qLXFEIiF9ZcnMqsmuP_U-vsGl9vrg-cEHy9xaBllBO3ykd8J_TYbcqGgI1MYizB4l9s65iaB4fbaNAuxdO0zEi6rjKrli8iuA-84IPqYH7zVL3-H_Fkltnr2ORzRNmNNd1tLfMFVNqhe-spcF3YaLin-5ZWrsSUVjUpIMCUsiGd8WMju3cDVOM9K_8yrIq964f6uBs_kjPmJhiaiXEn1mTnmCkTKg2n6zgJBlyYOtCpGCvNd98W_vHGnJcYlNTIiNMIKh705nO_x-SPC-UjcWnnSgTT4Q4uHrsCQKJQfvdyvQJv4cH_bZk2h477rwmNtyd3ijav15jWgKA" http://localhost/api/v1/senseddata
```

## Shortcuts
```
sudo rm -rf var/cache/* && sudo sudo rm -rf var/logs/*
```

