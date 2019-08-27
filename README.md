"# BileMo" 

run this to generate the API JWT Key

```
docker-compose exec php sh -c '
    set -e
    apk add openssl
    mkdir -p config/jwt
    jwt_passhrase=$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''='')
    echo "$jwt_passhrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    echo "$jwt_passhrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
'
```

See https://api-platform.com/docs/core/jwt/

And this to load the fixtures
docker-compose exec php bin/console hautelook:fixtures:load -n

And tests with code coverage
docker-compose exec php bin/phpunit --coverage-html tstst/html

To enable the blackfire profiler create blackfire-variables.env
and paste 
```
BLACKFIRE_CLIENT_ID=XXX
BLACKFIRE_CLIENT_TOKEN=XXX
BLACKFIRE_SERVER_ID=XXX
BLACKFIRE_SERVER_TOKEN=XXX
```
