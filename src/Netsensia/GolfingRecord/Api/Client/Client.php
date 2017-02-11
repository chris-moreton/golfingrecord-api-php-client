<?php
namespace Netsensia\GolfingRecord\Api\Client;

use GuzzleHttp\Message\Response;
use Netsensia\GolfingRecord\Api\Client\Traits\HttpClient;

class Client
{
    use HttpClient;
    
    /**
     * The base URI for the API
     */
    private $apiBaseUri = 'http://api.golfingrecord.local';
    
    /**
    * oAuth2 Password Grant
    * 
    * /oauth/token
    * 
    * @return boolean|mixed
    */
    public function passwordGrant($username, $password, $clientSecret, $scope = '*')
    {
        $response = $this->client()->request('POST', $this->apiBaseUri . '/oauth/token', $this->opts([
            'form_params' => [
                'grant_type' => 'password',
                'username' => $username,
                'password' => $password,
                'client_id' => 2,
                'client_secret' => $clientSecret,
                'scope' => $scope,
            ],
        ]));

        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
    
    /**
     * User
     * 
     * /user/{id}
     *
     * @return boolean|mixed
     */
    public function getUserDetails($id)
    {
        $response = $this->client()->request('GET', $this->apiBaseUri . '/user/' . $id, $this->opts());
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
}
