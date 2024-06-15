<?php

namespace app\controllers;


use Yii;
use yii\web\Response;
use GuzzleHttp\Client;
use yii\web\Controller;
use Psr\Http\Message\ResponseInterface;

class WebhookController extends Controller
{
    public function beforeAction($action)
{            
    $this->enableCsrfValidation = false;

    return parent::beforeAction($action);
}
    
public function actionIndex()
{
    $request = Yii::$app->request;
    $name = $request->post('name');
    $payload = $request->post('payload');
    $message = json_decode($payload, true);
    if($name == 'new message') {
        if($message['message']['text'] == 'ping') {
            $this->sendMessage();
            Yii::info($message['message']['text'], 'clients');
        }
        
    }
    
    if($name == 'success send') {
        Yii::info($message['message']['text'], 'operators');
    }

    return $this->asJson("");
}


/**
* Send message to dialog
* @throws GuzzleException
*/
    public function sendMessage(): ResponseInterface 
    {
        $client = new Client(['headers' => [
            'X-Auth-Token' => 'hRV35sKOT1hwk0unwZePbpMKVf1B_yInFdNSSOziLMFtMeg5igMIQAoUtpSqxudE']]);

            $response = $client->post('https://api.teletype.app/public/api/v1/message/send', [
                'multipart' => [
                    [
                    'name'     => 'dialogId',
                    'contents' => '3t8JrbVQzJA_YH1Tpn5PDTUXO8XpRHkr5lwbeQrFryeIMgk45taaPuuBZYfpXAfJ',
                    'headers'  => [
                        'Content-Type' => 'multipart/form-data',
                    ]
                    ],
                    [
                    'name'     => 'text',
                    'contents' => 'pong',
                    'headers'  => [
                        'Content-Type' => 'multipart/form-data',
                    ]
                    ]]
            ]);
            
            return $response;
    }
}
