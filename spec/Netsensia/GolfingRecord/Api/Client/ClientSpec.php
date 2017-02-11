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
        $this->getUserDetails('chris@chrismo.com')->shouldContainKeyAndValue('id', 28);
    }

    function it_can_get_user_details_from_an_id()
    {
        $this->getUserDetails(28)->shouldContainKeyAndValue('email', 'chris@chrismo.com');
    }

    function it_can_verify_a_user_password()
    {
        $this->verifyPassword('chris@chrismo.com', 'asdasd')->shouldContainKeyAndValue('verified', true);
    }
    
    function it_can_find_that_a_user_password_is_wrong()
    {
        $this->verifyPassword('chris@chrismo.com', 'qweqwe')->shouldContainKeyAndValue('verified', false);
    }
    
    public function getMatchers()
    {
        return [
            'containKeyAndValue' => function ($subject, $key, $value) {
                return !empty($subject) && property_exists($subject, $key) && $subject->$key == $value;
            }
        ];
    }
}
