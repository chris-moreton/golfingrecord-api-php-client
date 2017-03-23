# Golfing Record API PHP Client

This is a PHP client for the Golfing Record API which allows users of the Golfing Record service to manage their accounts and data programmatically.

You will need a client secret to use this client. The API is currently incomplete, so client secrets are not available for request.

Add to project using Composer
-----------------------------

composer require chris-moreton/golfingrecord-api-php-client
    
Usage
-----

Development
-----------

### Clone the repo and compose

    git clone git@github.com:chris-moreton/golfingrecord-api-php-client
    cd golfingrecord-api-php-client
    composer install

### Run the tests

Copy .test.config.example to .test.config and fill in the values.

Then you can run the tests:

    bin/phpspec run --format=pretty -vvv --stop-on-failure
