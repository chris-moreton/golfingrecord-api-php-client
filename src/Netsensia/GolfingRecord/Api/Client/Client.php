<?php
namespace Netsensia\GolfingRecord\Api\Client;

use GuzzleHttp\Message\Response;
use Netsensia\GolfingRecord\Api\Client\Traits\HttpClient;

class Client
{
    use HttpClient;
    
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
        $endpoint = '/v1/users/' . $id;
        
        $response = $this->client()->request('GET', $this->apiBaseUri . $endpoint, $this->opts());
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
    
    public function getUserDetailsFromOAuthId($id, $provider)
    {
        $endpoint = '/v1/oauth/users/' . $id . '/' . $provider;
    
        $response = $this->client()->request('GET', $this->apiBaseUri . $endpoint, $this->opts());
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
    
    /**
     * Create user
     *
     * @return boolean|mixed
     */
    public function createUser(array $details)
    {
        $response = $this->client()->request('POST', $this->apiBaseUri . '/v1/users', $this->opts(['json' => $details]));
    
        if( $response->getStatusCode() != 201 ) {
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
    
    /**
     * User courses
     *
     * /user/{id}/courses/{courseId}
     *
     * @param $id The user id or email
     *
     * @return boolean|mixed
     */
    public function getUserCourses($id, $courseId = '')
    {
        $response = $this->client()->request('GET', $this->apiBaseUri . '/v1/users/' . $id . '/courses/' . $courseId, $this->opts());
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
}
