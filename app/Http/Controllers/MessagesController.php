<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * @var string
     */
    protected $message;

    /**
     * MessagesController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->message = $request->input('message');
    }
}
