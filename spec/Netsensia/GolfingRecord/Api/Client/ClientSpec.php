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
        )->shouldContainKeyAndValue('token_type', 'Bearer');
    }
    
    function it_can_get_user_details_from_an_email_address()
    {
        $this->getUserDetails('chris@chrismo.com')->containKeyAndValue('id', 28);
    }

    function it_can_get_user_details_from_an_id()
    {
        $this->getUserDetails(28)->containKeyAndValue('email', 'chris@chrismo.com');
    }
    
    function it_can_verify_a_user_password()
    {
        $this->verifyPassword('chris@chrismo.com', 'asdasd');
    }
    
    public function getMatchers()
    {
        return [
            'containKeyAndValue' => function ($subject, $key, $value) {
                return property_exists($subject, $key) && $subject->$key == $value;
            }
        ];
    }
}
