# README.md

## Lockate API

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

