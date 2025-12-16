<?php

// app/Http/Controllers/GlobalChatController.php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

class GlobalChatController extends Controller
{
    public function index()
    {
        return view('chat.global');
    }
}

