# README.md

## Lockate API

## Important Production and Development changes

Check setting of **src/Lockate/APIBundle/EventListener/CapturedDataRequestListener.php**
```php
    const FORWARD_URI_DEV = 'http://localhost/app_dev.php';
    const FORWARD_URI_PROD = 'http://lockate.hopto.org';
```
And what is used in function `onCapturedDataRequest`


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

## Database
Update (due to changes)
```
$ php bin/console doctrine:schema:update --force
```
   
## Testing

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
$ ./phpunit-6.5.phar --filter testGetGatewayDataUsingGatewayId
$ ./phpunit-6.5.phar --filter testRetrieveGatewayThroughGatewayId
$ ./phpunit-6.5.phar --filter testRetrieveGatewaySensors
$ ./phpunit-6.5.phar --filter testRetrieveSensor
$ ./phpunit-6.5.phar --filter testRetrieveLastEvents
```

External requests section
```
$ ./phpunit-6.5.phar tests/Extra/SendExternalRequestsTest.php 
```

## Request examples using `curl`
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
curl -v -H "Content-Type: application/json" -X POST -d '{"gateway_record":[{"gateway_id": 0,"timestamp": "1521025377","node_record": [{"node_id": 27,"timestamp": "1521025377","ai": {"rssi": -86.0},"txt": {"mac": "90:e7:c4:xx:xx:xx", "company": "HTC Corporation"}}]}]}' -H "Authorization: Bearer eyJhbGciOiJSUzI1NiJ9.eyJ1c2VybmFtZSI6InVubyIsImV4cCI6MTUyMTMwNDM2MCwiaWF0IjoxNTIxMzAwNzYwfQ.w9Tb9XOz56T4kQSs_xF-mzBBMp3yPOsWzLwYS_Vk9_JCupyZXx9k0RdGoT3-JXu78Yf5BtaJxz9dUY1VuQObucgD1aXkvDb9xf-Bi4oaVa6G10mMlQfdwJGTX4b2RjF5szdykhwb_YJCFew4_c9UQ5rsAuK0lceQZhaflt9IcjA7jJgu3kfQS8XmX3MAv5lzMfmXe9QL-mDTV38kRyjeB1SPerSD9jtlJxDw7YgUlMpZxnf1uAbEgLPkQx6ufx_njHNzZKJIUgLo2B0gFwQJ5HEz9fpMQIPI0pPdbAPSLO9d5DnlwneKd0q_L0hX1xf2vjcDM-Qc1_cjPo9Qw4EAh1xYuJ61Fe9xsLBCWt5-NTJAhjCverAjSwwO9oTTHHtShS49J8RL-cvEvsc1pdfehPWsaGlR8MgKkaubb0T5Lazx9PeUR5SKWwDpDXw0OvreCv7tSraK19eRyKvBfBlz2ZlXrQuYFYaiRI4_rpAzQOBzTkql5Dk6hqKPD7E95w6eWWTdHE6hqok-_FhlnWyqtZcl6t9j-hke2qNf1DDfUjc_KeYhmWdmo0Z1KqQI5s2hUCrR7z2TQUe6WQxEElzfgSyPU9ssc8QntcMomCk8m6iK7nu2hV1dXeQT2paA2VXdxvEWLGw2-8QaYvP7davdYml8Vb7lmrVXSxS5J1sa9BY" http://localhost/api/v1/senseddata
```

Sensor data post without token
```
curl -v -H "Content-Type: application/json" -X POST -d '{"gateway_record":[{"gateway_id": 0,"timestamp": "1521025377","node_record": [{"node_id": 27,"timestamp": "1521025377","ai": {"rssi": -86.0},"txt": {"mac": "90:e7:c4:xx:xx:xx", "company": "HTC Corporation"}}]}]}' http://localhost/api/v1/senseddata
```

#### Gateway sensors

```
$ curl -v http://localhost/api/v1/gateway/0 -H "Authorization: Bearer eyJhbGciOiJSUzI1NiJ9.eyJ1c2VybmFtZSI6InVubyIsImV4cCI6MTUyMTMwNDM2MCwiaWF0IjoxNTIxMzAwNzYwfQ.w9Tb9XOz56T4kQSs_xF-mzBBMp3yPOsWzLwYS_Vk9_JCupyZXx9k0RdGoT3-JXu78Yf5BtaJxz9dUY1VuQObucgD1aXkvDb9xf-Bi4oaVa6G10mMlQfdwJGTX4b2RjF5szdykhwb_YJCFew4_c9UQ5rsAuK0lceQZhaflt9IcjA7jJgu3kfQS8XmX3MAv5lzMfmXe9QL-mDTV38kRyjeB1SPerSD9jtlJxDw7YgUlMpZxnf1uAbEgLPkQx6ufx_njHNzZKJIUgLo2B0gFwQJ5HEz9fpMQIPI0pPdbAPSLO9d5DnlwneKd0q_L0hX1xf2vjcDM-Qc1_cjPo9Qw4EAh1xYuJ61Fe9xsLBCWt5-NTJAhjCverAjSwwO9oTTHHtShS49J8RL-cvEvsc1pdfehPWsaGlR8MgKkaubb0T5Lazx9PeUR5SKWwDpDXw0OvreCv7tSraK19eRyKvBfBlz2ZlXrQuYFYaiRI4_rpAzQOBzTkql5Dk6hqKPD7E95w6eWWTdHE6hqok-_FhlnWyqtZcl6t9j-hke2qNf1DDfUjc_KeYhmWdmo0Z1KqQI5s2hUCrR7z2TQUe6WQxEElzfgSyPU9ssc8QntcMomCk8m6iK7nu2hV1dXeQT2paA2VXdxvEWLGw2-8QaYvP7davdYml8Vb7lmrVXSxS5J1sa9BY"
```
```

## Development Shortcuts
```
sudo rm -rf var/cache/* && sudo sudo rm -rf var/logs/*
```

