<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;


final class AllTest extends TestCase
{
    protected $client;
    protected $cookieJar;
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new GuzzleHttp\Client(["base_uri" => "http://localhost:80",]);

        $this->cookieJar = new SessionCookieJar('cookie_file', true);
    }

    /* 
     * Function to test "Register" functionality. Please change the username before
     * every test
     * 
     * @change_parameters
     */
    public function testPOST_CreateUser(): void
    {
        parent::setUp();

        $parameters = [
            'username' => 'test2',
            'password' => '1234567890',
            'confirm_password' => '1234567890',
        ];

        // Convert the parameters to a JSON string if there are two or more parameters, and skip this step if you pass only one parameter
        $json_parameters = json_encode($parameters);
        // Send the POST request
        $response = $this->client->request('POST', 'index.php/user/register', [
            'body' => $json_parameters,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        // Get the response body
        $responseBody = $response->getBody()->getContents();

        // Decode the JSON response
        $responseData = json_decode($responseBody, true);

        // Assert if 'success' key exists and its value is true
        $this->assertArrayHasKey('success', $responseData);
        $this->assertTrue($responseData['success']);



    }

    public function testPOST_LoginUser(): void
    {
        parent::setUp();

        $parameters = [
            'username' => 'test1',
            'password' => '1234567890',
        ];

        // Convert the parameters to a JSON string if there are two or more parameters, and skip this step if you pass only one parameter
        $json_parameters = json_encode($parameters);
        // Send the POST request
        $response = $this->client->request('POST', 'index.php/user/login', [
            'body' => $json_parameters,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'cookies' => $this->cookieJar, // Use the cookie jar for handling cookies
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->cookieJar = $this->client->getConfig('cookies');


        // Get the response body
        $responseBody = $response->getBody()->getContents();

        // Decode the JSON response
        $responseData = json_decode($responseBody, true);

        // Assert if 'success' key exists and its value is true
        $this->assertArrayHasKey('success', $responseData);
        $this->assertTrue($responseData['success']);

    }

    public function testPOST_FailedLogin(): void
    {
        parent::setUp();

        $parameters = [
            'username' => 'test1',
            'password' => '123456789', // wrong password
        ];

        // Convert the parameters to a JSON string if there are two or more parameters, and skip this step if you pass only one parameter
        $json_parameters = json_encode($parameters);
        // Send the POST request
        $response = $this->client->request('POST', 'index.php/user/login', [
            'body' => $json_parameters,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        // Get the response body
        $responseBody = $response->getBody()->getContents();

        // Decode the JSON response
        $responseData = json_decode($responseBody, true);

        // Assert if 'success' key exists and its value is true
        $this->assertArrayHasKey('success', $responseData);
        $this->assertFalse($responseData['success']);

    }

    public function testGet_SongList()
    {
        $response = $this->client->request('GET', 'index.php/music/list');
        $this->assertEquals(200, $response->getStatusCode());

        $content = $response->getBody()->getContents();
        $jsonArray = json_decode($content, true);

        // Check if the response body is an array
        $this->assertIsArray($jsonArray);

        // Check the structure of each JSON object in the array
        foreach ($jsonArray as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('username', $item);
            $this->assertArrayHasKey('artist', $item);
            $this->assertArrayHasKey('song', $item);
            $this->assertArrayHasKey('rating', $item);
        }
    }

    /*
     *  @change_parameters
     *  
     */
    public function testPOST_NewSong()
    {
        parent::setUp();

        $parameters = [
            'artist' => 'test_artist_4',
            'song' => 'test_song_4',
            'rating' => '4',
        ];

        // Convert the parameters to a JSON string if there are two or more parameters, and skip this step if you pass only one parameter
        $json_parameters = json_encode($parameters);
        // Send the POST request
        $response = $this->client->request('POST', 'index.php/music/create', [
            'body' => $json_parameters,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'cookies' => $this->cookieJar, // Use the cookie jar for handling cookies
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        // Get the response body
        $responseBody = $response->getBody()->getContents();

        // Decode the JSON response
        $responseData = json_decode($responseBody, true);

        // Assert if 'success' key exists and its value is true
        $this->assertArrayHasKey('success', $responseData);
        $this->assertTrue($responseData['success']);
    }

    /*
     *  @change_parameters
     *  
     */
    public function testPOST_UpdateSong()
    {
        parent::setUp();

        $parameters = [
            'id' => 55,
            'artist' => 'update_artist_1',
            'song' => 'update_song_1',
            'rating' => '3',
        ];

        // Convert the parameters to a JSON string if there are two or more parameters, and skip this step if you pass only one parameter
        $json_parameters = json_encode($parameters);
        // Send the POST request
        $response = $this->client->request('POST', 'index.php/music/update', [
            'body' => $json_parameters,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'cookies' => $this->cookieJar, // Use the cookie jar for handling cookies
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        // Get the response body
        $responseBody = $response->getBody()->getContents();

        // Decode the JSON response
        $responseData = json_decode($responseBody, true);

        // Assert if 'success' key exists and its value is true
        $this->assertArrayHasKey('success', $responseData);
        $this->assertTrue($responseData['success']);
    }
    /*
     *  @change_parameters
     *  
     */
    public function testPOST_DeleteSong()
    {
        parent::setUp();

        $parameters = [
            'id' => 56
        ];

        // Convert the parameters to a JSON string if there are two or more parameters, and skip this step if you pass only one parameter
        $json_parameters = json_encode($parameters);
        // Send the POST request
        $response = $this->client->request('POST', 'index.php/music/delete', [
            'body' => $json_parameters,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'cookies' => $this->cookieJar, // Use the cookie jar for handling cookies
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        // Get the response body
        $responseBody = $response->getBody()->getContents();

        // Decode the JSON response
        $responseData = json_decode($responseBody, true);

        // Assert if 'success' key exists and its value is true
        $this->assertArrayHasKey('success', $responseData);
        $this->assertTrue($responseData['success']);
    }


    public function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
    }


}


