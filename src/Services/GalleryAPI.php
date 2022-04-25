<?php
namespace Elab\Lite\Services;
use GuzzleHttp\Client;

class GalleryAPI
{

    public $client;
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => GALLERY_API_HOST,
        ]);
    }

  
    private function apiCall($cmd, $req = array(), $method = 'POST', $extra = '')
    {

        try {
            /**
             * We use Guzzle to make an HTTP request somewhere in the
             * following theMethodMayThrowException().
             */
            $parameterAttribute = NULL;
            if($extra == 'gallery'){
                //$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvZ2FsbGVyeS1hcGkuZW5naW5lLmx0XC9hcGlcL2F1dGhcL2xvZ2luIiwiaWF0IjoxNjQ2NzIzOTA3LCJleHAiOjE2NDY3Mjc1MDcsIm5iZiI6MTY0NjcyMzkwNywianRpIjoiRGdXMzAzMWdTMDN0NGtvdiIsInN1YiI6MzYsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.UR3Ltl71EP9kWeQUqviQtaTRMlsOgGET7Xq2B_UhrEM';
                $token = $_SESSION['gallery_access_token'];
                $parameterAttribute = ['headers' =>
                    [
                        'Authorization' => 'Bearer ' . $token,
                        'Accept'        => 'application/json',]
                ];
            }else{
                $parameterAttribute = ['json' => $req];
            }


            $res = $this->client->request(strtoupper($method), 'api'.$cmd, $parameterAttribute);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            /**
             * Here we actually catch the instance of GuzzleHttp\Psr7\Response
             * (find it in ./vendor/guzzlehttp/psr7/src/Response.php) with all
             * its own and its 'Message' trait's methods. See more explanations below.
             *
             * So you can have: HTTP status code, message, headers and body.
             * Just check the exception object has the response before.
             */
            if ($e->hasResponse()) {
                $res = $e->getResponse();
            }
        }

        return $res;
    }


    /************************************************************************
     *          User Login Method
     */
    public function login($email, $password)
    {
        $args = array(
            "email" => $email,
            "password" => $password
        );
        $respondingFromApi = $this->apiCall('/auth/login',  $args);

        if($respondingFromApi->getStatusCode() == 200){
            $body = $respondingFromApi->getBody();
            $arr_body = json_decode($body);
            $_SESSION['gallery_access_token'] = $arr_body->access_token;

            $output['token'] =  $arr_body->access_token;
            $output['status'] = true;
            $output['statusCode'] = 200;
            $output['message'] = 'User Login Successfully Completed..';
            return $output;
        }else{
            $output['status'] = false;
            $output['statusCode'] = $respondingFromApi->getStatusCode();
            $output['message'] = 'User Login Operation Is Failed';
            return $output;
        }

    }

    /************************************************************************
     *          User Registration Method
     */
    public function register($name, $email, $password, $password_confirmation)
    {

        if($password != $password_confirmation){
            $output['status'] = false;
            $output['statusCode'] = 424;
            $output['message'] = 'password and confirm password should be same';
            return $output;
        }

        $args = array(
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "password_confirmation" => $password_confirmation
        );
        $respondingFromApi = $this->apiCall('/auth/register',  $args);

        if($respondingFromApi->getStatusCode() == 201){
            $body = $respondingFromApi->getBody();
            $arr_body = json_decode($body);

            $output['status'] = true;
            $output['statusCode'] = 201;
            $output['message'] = 'User Registration Successfully Completed';

        }else{
            $output['status'] = false;
            $output['statusCode'] = $respondingFromApi->getStatusCode();
            $output['message'] = 'User Registration  Is Failed';

        }
        return $output;

    }

    /************************************************************************
     *          Gallery Method
     */
    public function gallery($id = '')
    {

        // Login API For Authentication
        if(!isset($_SESSION['gallery_access_token'])){
            $this->login(api_email, api_password);
        }

         $galleryId = $id;
         $respondingFromApi = $this->apiCall('/gallery/'.$galleryId,  array(), 'get', 'gallery');
         $respondingFromApi->getStatusCode();
         $body = $respondingFromApi->getBody();
        

        if($respondingFromApi->getStatusCode() == 200){
            $body = $respondingFromApi->getBody();
            $output['gallery'] = json_decode($body);
            $output['status'] = true;
            $output['statusCode'] = $respondingFromApi->getStatusCode();
            $output['message'] = 'Product Image Retrieve From API';

        }else{
            
            $output['status'] = false;
            $output['statusCode'] = $respondingFromApi->getStatusCode();
            $output['message'] = 'User Login Operation Is Failed';
            // Login API For Authentication
            $this->login(api_email, api_password);
        }
        return $output;
    }
  
}