<?php

namespace MVC\Controllers;

use MVC\Core\App;
use MVC\Core\Controller;
use MVC\Models\ShortLinkModel;

class ResolveController extends Controller
{
    public function handle()
    {
        $this->model = new ShortLinkModel();

        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim($uri, '/');

        $urlPair = $this->model->getRowByUri($uri);
        if (!empty($urlPair)) {
            $originalUrl = $urlPair['original_url'];
            header('Location: ' . $originalUrl, true, 301);
        } else {
            header('HTTP/1.1 404 Not Found');
        }
    }
}