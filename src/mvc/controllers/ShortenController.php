<?php

namespace MVC\Controllers;

use MVC\Core\App;
use MVC\Core\Controller;
use MVC\Models\ShortLinkModel;

class ShortenController extends Controller
{
    public function handle()
    {
        $this->model = new ShortLinkModel();

        $postData = json_decode(file_get_contents('php://input'), true);
        $originalUrl = $postData['original'];

        if(!filter_var($originalUrl, FILTER_VALIDATE_URL)) {
            exit('Not a valid url');
        }

        $checkExistingPair = $this->model->getRowByOriginalUrl($originalUrl);
        if (!empty($checkExistingPair)) {
            //без UI просто отдаём ссылку в ответе на запрос из контроллера
            $response = [
                'short_link' => App::$config['host'] . '/' .  $checkExistingPair['short_uri']
            ];
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit();
        }

        $isDuplicateUri = false;
        
        $shortUri = '';

        do {
            $shortUri = $this->generateShortUri(App::$config['urilen']);

            if(!empty($this->model->getRowByUri($shortUri))) {
                $isDuplicateUri = true;
            }
        } while($isDuplicateUri);

        $this->model->setShortUri($shortUri)
            ->setOriginalLink($originalUrl)
            ->saveToDb();

        //без UI просто отдаём ссылку в ответе на запрос из контроллера
        $response = [
            'short_link' => App::$config['host'] . '/' .  $shortUri
        ];
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    private function generateShortUri(int $length): string
    {
        $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shortUri = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomChar = $charset[rand(0, strlen($charset)-1)];
            $shortUri .= $randomChar;
        }

        return $shortUri;
    }
}