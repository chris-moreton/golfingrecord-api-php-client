<?php
namespace spec\Netsensia\GolfingRecord\Api\Client;

include "spec/SpecHelper.php";

use PhpSpec\ObjectBehavior;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Netsensia\GolfingRecord\Api\Client\Client');
    }
    
    function it_can_get_a_bearer_token_using_password_grant()
    {
        $this->passwordGrant(
            config('USERNAME'),
            config('PASSWORD'),
            config('PASSWORD_GRANT_CLIENT_SECRET')
        )->shouldBeAnObjectContainingKeyAndValue('token_type', 'Bearer');
    }
    
    function it_can_get_user_details_from_an_email_address()
    {
        $this->getUserDetails('chris@chrismo.com')->shouldBeAnObjectContainingKeyAndValue('id', config('USER_ID'));
    }

    function it_can_get_user_details_from_an_id()
    {
        $this->getUserDetails(config('USER_ID'))->shouldBeAnObjectContainingKeyAndValue('email', 'chris@chrismo.com');
    }

    function it_can_verify_a_user_password()
    {
        $this->verifyPassword('chris@chrismo.com', config('PASSWORD'))->shouldBeAnObjectContainingKeyAndValue('verified', true);
    }
    
    function it_can_find_that_a_user_password_is_wrong()
    {
        $this->verifyPassword('chris@chrismo.com', 'qweqwe')->shouldBeAnObjectContainingKeyAndValue('verified', false);
    }
    
    function it_can_update_a_user_remember_token()
    {
        $r = md5(rand(0, PHP_INT_MAX));
        $this->updateUserDetails(config('USER_ID'), [
            'remember_token' => $r,
        ])->shouldBeAnObjectContainingKeyAndValue('remember_token', $r);
    }
    
    function it_will_report_an_attempt_to_update_an_invalid_user_field()
    {
        $this->shouldThrow('GuzzleHttp\Exception\ClientException')->during('updateUserDetails', [config('USER_ID'), [
            'remembers_token' => 123,
        ]]);
    }
    
    function it_will_report_an_attempt_to_update_an_invalid_user()
    {
        $this->shouldThrow('GuzzleHttp\Exception\ClientException')->during('updateUserDetails', [-1, [
            'remember_token' => 123,
        ]]);
    }
    
    function it_can_create_a_new_user()
    {
        $time = time();
        $username = 'User' . $time;
        $this->createUser([
            'name' => $username, 
            'email' => $username . '@netsensia.com', 
            'password' => 'Pass' . $username]
        )->shouldBeAnObjectContainingKeyAndValue('name', $username);
    }

    function it_will_report_an_attempt_to_create_a_user_with_an_invalid_field()
    {
        $time = time();
        $this->shouldThrow('GuzzleHttp\Exception\ClientException')->during('createUser', [[
            'names' => 'User' . $time, 
            'email' => $time . '@netsensia.com', 
            'password' => 'Pass' . $time
        ]]);
    }
    
    public function getMatchers()
    {
        return [
            'beAnObjectContainingKeyAndValue' => function ($subject, $key, $value) {
                return !empty($subject) && property_exists($subject, $key) && $subject->$key == $value;
            }
        ];
    }
}
