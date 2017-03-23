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
