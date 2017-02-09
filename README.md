# Companies House API PHP Client

You will need a client secret to use this client. As the API is current incomplete, I'm not yet giving them out.

Add to project using Composer
-----------------------------

composer require chris-moreton/golfingrecord-api-php-client
    
Usage
-----

## oAuth2 Password grant

    $client = new Client();
    $response = $client->passwordGrant($username, $password, $clientSecret, $scope);
    

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
