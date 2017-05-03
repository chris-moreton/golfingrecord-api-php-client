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
        return $this->simpleGet('/v1/users/' . $id);
    }
    
    public function getUserDetailsFromOAuthId($id, $provider)
    {
        return $this->simpleGet('/v1/oauth/users/' . $id . '/' . $provider);
    }
    
    /**
     * User courses
     *
     * /user/{id}/courses
     *
     * @param $id The user id
     *
     * @return boolean|mixed
     */
    public function getUserCourses($id)
    {
        return $this->simpleGet('/v1/users/' . $id . '/courses');
    }
    
    /**
     * User friends
     *
     * /user/{id}/friends
     *
     * @param $id The user id
     *
     * @return boolean|mixed
     */
    public function getUserFriends($id)
    {
        return $this->simpleGet('/v1/users/' . $id . '/friends');
    }
    
    /**
     * User courses
     *
     * /user/{id}/courses
     *
     * @param $id The user id
     *
     * @return boolean|mixed
     */
    public function getUserFriendCourses($id)
    {
        return $this->simpleGet('/v1/users/' . $id . '/friends/courses');
    }
    
    public function getDiagnostics()
    {
        return $this->simpleGet('/v1/diagnostics');
    }
    
    public function getTees()
    {
        return $this->simpleGet('/v1/tees');
    }
    
    /**
     * Create user friend
     *
     * /user/{id}/friends
     *
     * @param $id The user id
     *
     * @return boolean|mixed
     */
    public function createUserFriend($id, array $details)
    {
        $response = $this->client()->request('POST', $this->apiBaseUri . '/v1/users/' . $id . '/friends', $this->opts(['json' => $details]));
    
        if( $response->getStatusCode() != 201 ) {
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
     * Create course
     *
     * @return boolean|mixed
     */
    public function createCourse($id, array $details)
    {
        $response = $this->client()->request('POST', $this->apiBaseUri . '/v1/users/' . $id . '/courses', $this->opts(['json' => $details]));
        
        if( $response->getStatusCode() != 201 ) {
            return $this->log($response, false);
        }
        
        $jsonDecode = json_decode($response->getBody());
        
        $this->log($response, true);
        
        return $jsonDecode;
    }
}
