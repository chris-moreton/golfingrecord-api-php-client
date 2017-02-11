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
     * @param $id The user id or email
     * 
     * @return boolean|mixed
     */
    public function getUserDetails($id)
    {
        $response = $this->client()->request('GET', $this->apiBaseUri . '/v1/users/' . $id, $this->opts());
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }

    /**
     * Verify password
     * 
     * @param string $email
     * @param string $password
     * 
     * @return boolean|mixed
     */
    public function verifyPassword($email, $password)
    {
        $response = $this->client()->request('POST', $this->apiBaseUri . '/v1/users/' . $email . '/passwordcheck', $this->opts([
            'form_params' => [
                'password' => $password,
            ],
        ]));
        
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
        
        $jsonDecode = json_decode($response->getBody());
        
        $this->log($response, true);
        
        return $jsonDecode;
    }
}
