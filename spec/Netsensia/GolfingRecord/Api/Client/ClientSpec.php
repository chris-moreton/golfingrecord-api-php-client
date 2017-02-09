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
    
    /**
     * oAuth2 Password Grant
     */
    function it_can_get_a_bearer_token_using_password_grant()
    {
        $this->passwordGrant(
            config('USERNAME'),
            config('PASSWORD'),
            config('PASSWORD_GRANT_CLIENT_SECRET')
        )->shouldContainKeyAndValue('token_type', 'Bearer');
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
