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

        $originalUrl = $_POST['original'];
        if(!filter_var($originalUrl, FILTER_VALIDATE_URL)) {
            exit('Not a valid url');
        }

        $checkExistingPair = $this->model->getRowByOriginalUrl($originalUrl);
        if (!empty($checkExistingPair)) {
            //без UI просто отдаём ссылку в ответе на запрос из контроллера
            echo App::$config['host'] . '/' .  $checkExistingPair['short_uri'] . '/';
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
        echo App::$config['host'] . '/' .  $shortUri . '/';
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