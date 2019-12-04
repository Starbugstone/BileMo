# BileMo

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Starbugstone/BileMo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Starbugstone/BileMo/?branch=master)

The project is based on the docker-compose file from API platform.
Simply download the project and run docker-compose up from the root folder of the project. 
The migrations for the database will load on first startup and all the components will load.

create a jwt passphrase in the env file then generate the jwt key
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

And this to reload the fixtures
docker-compose exec php bin/console hautelook:fixtures:load -n

The fixtures will load a test admin and test clients (client1 to 4), all with the password 'password'

In dev environment, a special login form is accessible via /client_login_form

The documentation is taken care of by OpenAPI and is readable via the /docs URL

The project has some unit and functional tests that can be run with phpunit.
docker-compose exec php bin/phpunit

To get to the interface, just navigate to localhost and api platform will give you some nice tools to access the API.

Or you can just navigate to :

* client: [https://localhost:8443/docs](https://localhost:8443/docs)
* Admin: [https://localhost:444](https://localhost:444)

For the admin to work, you need to accept the self signed https certificate of the API and login 
  
