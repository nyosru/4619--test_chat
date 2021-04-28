<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class QueueSenderEmail implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $mess = '';
    public $email = '';

    /**
     * Количество попыток выполнения задания.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email = '', $mess = 'blank message') {
        $this->email = $email;
        $this->mess = $mess;
    }

    // когда это указано ... то количество запусков ограничено временем до успешного завершения
    /**
     * Задать временной предел попыток выполнить задания.
     *
     * @return \DateTime
     */
//    public function retryUntil() {
//        return now()->addMinutes(1);
//    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        // throw new \Exception('test error', 2);
        // $this->fail();

        // $toEmail = "1@php-cat.com";
        $mm = new SendMail($this->mess ?? 'что то пошло не так ' . __FILE__ . ' #' . __LINE__);
        Mail::to($this->email)->send($mm);

        // шлём сообщние в телегу
//        $r1 = ['http://', 'https://'];
//        $r2 = ['', ''];
//        \Nyos\Msg::$HTTP_HOST = str_replace($r1, $r2, url('/'));
//        \Nyos\Msg::sendTelegramm($this->mess, null, 2);
    }

}
