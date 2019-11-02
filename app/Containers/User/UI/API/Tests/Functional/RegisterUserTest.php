<?php

namespace App\Containers\User\UI\API\Tests\Functional;

use App\Containers\User\Tests\ApiTestCase;

/**
 * Class RegisterUserTest.
 *
 * @group user
 * @group api
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class RegisterUserTest extends ApiTestCase
{

    protected $endpoint = 'post@v1/register';

    protected $auth = false;

    protected $access = [
        'roles'       => '',
        'permissions' => '',
    ];

    /**
     * @test
     */
    public function testRegisterNewUserWithCredentials_()
    {
        $data = [
            'email'    => 'apiato@mail.dev',
            'name'     => 'Apiato',
            'password' => 'secretpass',
        ];

        // send the HTTP request
        $response = $this->makeCall($data);

        // assert response status is correct
        $response->assertStatus(200);

        $this->assertResponseContainKeyValue([
            'email' => $data['email'],
            'name'  => $data['name'],
        ]);

        $responseContent = $this->getResponseContentObject();

        $this->assertNotEmpty($responseContent->data);

         // assert the data is stored in the database
        $this->assertDatabaseHas('users', ['email' => $data['email']]);
    }

    /**
     * @test
     */
    public function testRegisterNewUserUsingGetVerb()
    {
        $data = [
            'email'    => 'apiato@mail.dev',
            'name'     => 'Apiato',
            'password' => 'secret',
        ];

        // send the HTTP request
        $response = $this->endpoint('get@v1/register')->makeCall($data);

        // assert response status is correct
        $response->assertStatus(405);

        $this->assertResponseContainKeyValue([
            'errors' => 'Method Not Allowed!',
        ]);
    }

    /**
     * @test
     */
    public function testRegisterExistingUser()
    {
        $userDetails = [
            'email'    => 'apiato@mail.dev',
            'name'     => 'Apiato',
            'password' => 'secret',
        ];

        // get the logged in user (create one if no one is logged in)
        $this->getTestingUser($userDetails);

        $data = [
            'email'    => $userDetails['email'],
            'name'     => $userDetails['name'],
            'password' => $userDetails['password'],
        ];

        // send the HTTP request
        $response = $this->makeCall($data);

        // assert response status is correct
        $response->assertStatus(422);

        $this->assertValidationErrorContain([
            'email' => 'The email has already been taken.',
        ]);
    }

    /**
     * @test
     */
    public function testRegisterNewUserWithoutEmail()
    {
        $data = [
            'name'     => 'Apiato',
            'password' => 'secret',
        ];

        // send the HTTP request
        $response = $this->makeCall($data);

        // assert response status is correct
        $response->assertStatus(422);

        // assert response contain the correct message
        $this->assertValidationErrorContain([
            'email' => 'The email field is required.',
        ]);
    }

    /**
     * @test
     */
    public function testRegisterNewUserWithoutName()
    {
        $data = [
            'email'    => 'apiato@mail.dev',
            'password' => 'secret',
        ];

        // send the HTTP request
        $response = $this->makeCall($data);

        // assert response status is correct
        $response->assertStatus(422);

        // assert response contain the correct message
        $this->assertValidationErrorContain([
            'name' => 'The name field is required.',
        ]);
    }

    /**
     * @test
     */
    public function testRegisterNewUserWithoutPassword()
    {
        $data = [
            'email' => 'apiato@mail.dev',
            'name'  => 'Apiato',
        ];

        $response = $this->makeCall($data);

        // assert response status is correct
        $response->assertStatus(422);

        // assert response contain the correct message
        $this->assertValidationErrorContain([
            'password' => 'The password field is required.',
        ]);
    }

    /**
     * @test
     */
    public function testRegisterNewUserWithInvalidEmail()
    {
        $data = [
            'email'    => 'missing-at.dev',
            'name'     => 'Apiato',
            'password' => 'secret',
        ];

        // send the HTTP request
        $response = $this->makeCall($data);

        // assert response status is correct
        $response->assertStatus(422);

        // assert response contain the correct message
        $this->assertValidationErrorContain([
            'email' => 'The email must be a valid email address.',
        ]);
    }
}
