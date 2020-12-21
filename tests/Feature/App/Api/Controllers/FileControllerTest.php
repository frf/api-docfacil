<?php

namespace Feature\App\Api\Controllers;

use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    private $userData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userData = [
            'email' => 'fabio@fabiofarias.com.br',
            'password' => '123456',
        ];
    }

    public function testLogin()
    {
        $userLogin = [
            'email' => $this->userData['email'],
            'password' => $this->userData['password']
        ];

        $response = $this->post('/v1/auth/login', $userLogin);

        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token',
        ]);

        return json_decode($response->getContent());
    }

    /**
     * @depends testLogin
     */
    public function testUploadBase64($token)
    {
        $dataFile = base_path('tests/Feature/image.json');
        $contentFile = file_get_contents($dataFile);
        $dataJson = json_decode($contentFile);

        $dataUpload = [
            'file'=>$dataJson->file,
            'name'=>$dataJson->name,
            'type'=>$dataJson->type,
        ];

        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token->access_token
        )->post('/v1/file/upload/base64', $dataUpload);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'url',
            ]
        ]);
    }
}
