<?php

namespace Apps\Controllers;

class NotFoundController extends Controller
{
    public function index()
    {
        http_response_code(404);
        $this->render('pageNotFound');
        die();
    }
}
