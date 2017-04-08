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
    
    function it_can_get_user_details_from_an_id_using_a_user_token()
    {
        $this->beConstructedWith(config('API_URI'), config('API_USER_KEY'));
        $this->getUserDetails(config('USER_ID'))->shouldBeAnObjectContainingKeyAndValue('id', config('USER_ID'));
    }

    function it_can_get_user_details_from_an_id_using_an_admin_token()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));
        $this->getUserDetails(config('USER_ID'))->shouldBeAnObjectContainingKeyAndValue('id', config('USER_ID'));
    }

    function it_can_create_a_user()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));
        $name = time();
        $this->createUser(['realname' => $name, 'oauth_id' => md5($name), 'oauth_provider' => 'test'])->shouldBeAnObjectContainingKeyAndValue('realname', $name);
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
