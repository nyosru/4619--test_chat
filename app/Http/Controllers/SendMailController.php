<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\QueueSenderEmail;

class SendMailController extends Controller {

    public function index() {
        return view('send-email');
    }

    public function send($message) {
        $qs = new QueueSenderEmail($message);
        $this->dispatch($qs);

        return redirect()
                        ->back()
                        ->with('mess', "Сообщение $message отправлено");
    }

}
