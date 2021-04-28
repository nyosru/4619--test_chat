<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Requests\TicketRequest;
use \App\Models\Ticket;
use Validator;
// use App\Mail;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;
use App\Jobs\QueueSenderEmail;

class TicketsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
//
        // return response()->json($request->all());
        return response()->json(Ticket::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
// public function store( TicketRequest $request) {
    public function store(Request $request) {

        /**
         * шлём одно сообщение
         */
        if ($request->type == 'send1message') {

            $toEmail = $request->mailto ?? "1@php-cat.com";
            // $mm = new SendMail($this->mess);
            // $mm = new SendMail('Привет буфет');
            // Mail::to($toEmail)->send($mm);
//            Mail::to($request->user())->send(new OrderShipped($order));
            $ee = Mail::to($toEmail)->send(new OrderShipped($request));

            return response()->json(['status' => 'okey', 'res' => $ee]);
        }
        /**
         * шлём много сообщений в очередь
         */ elseif ($request->type == 'send_messages_Queue') {

//            $toEmail = $request->mailto ?? "1@php-cat.com";
//            // $mm = new SendMail($this->mess);
//            // $mm = new SendMail('Привет буфет');
//            // Mail::to($toEmail)->send($mm);
////            Mail::to($request->user())->send(new OrderShipped($order));
//
//            $ee = Mail::to($toEmail)->send(new OrderShipped($request));

            /**
             * пример из документации
             */
            // use App\Jobs\ProcessPodcast;
            // Это задание отправляется в очередь `default` соединения по умолчанию ...
            //ProcessPodcast::dispatch();
            // Это задание отправляется в очередь `emails` соединения по умолчанию ...
            //ProcessPodcast::dispatch()->onQueue('emails');
// рабочий вариант
            if (1 == 2) {

                $message = 'messaga ' . rand(1, 9999);

                $qs = (new QueueSenderEmail($message));
                $this->dispatch($qs);

                $qs = (new QueueSenderEmail($message))->delay(now()->addMinutes(1));
                $this->dispatch($qs);

//            $qs = (new QueueSenderEmail($message))->delay(now()->addMinutes(2));
//            $this->dispatch($qs);
//
//            $qs = (new QueueSenderEmail($message))->delay(now()->addMinutes(2));
//            $this->dispatch($qs);
//
//            $qs = (new QueueSenderEmail($message))->delay(now()->addMinutes(2));
//            $this->dispatch($qs);
            }

            // if ($request->cancel_type == 'sale' || $request->sell_type == 'bron') {


            $message = '';
            $emails = [];

            if (
            // завершена продажа билетов на рейс; 
                    $request->cancel_type == 'finish_sale'
                    // рейс отменён
                    || $request->cancel_type == 'flight_cancel'
            ) {

                // $list_email = Ticket::all();
                // $list_email = Ticket::->groupBy('account_id')
                // $list_email = Ticket::where('email', '!=', NULL)
                $list_email = Ticket::select(['email'])

                        // новенькое 2104161543 проверить
                        ->whereNotNull('email')
                        ->groupBy('email')
                        // ->where('email', '!=', NULL)
                        ->get();
                ;

                foreach ($list_email->toArray() as $k => $v) {

                    // новенькое 2104161543 проверить
//                    if (empty($v['email']))
//                        continue;

                    $emails[] = $v['email'];
                }

                // завершена продажа билетов на рейс; 
                if ($request->cancel_type == 'finish_sale') {
                    $message = 'Продажа билетов закончена';
                }
                // рейс отменён
                elseif ($request->cancel_type == 'flight_cancel') {
                    $message = 'Рейс отменён';
                }
            }

            if (!empty($emails) && !empty($message)) {

                foreach ($emails as $email) {
                    $qs = (new QueueSenderEmail($email, $message));
                    $this->dispatch($qs);
                }
            }

            return response()->json([
                        'status' => 'okey',
                        'res' => $ee ?? 'x',
                        'req' => $request->All(),
                        'req2' => $list_email->toArray() ?? [],
                        'emails' => $emails ?? []
            ]);
            
        }
        // добавляем бронь и продажу билета
        elseif ($request->sell_type == 'sale' || $request->sell_type == 'bron') {

            $validator = Validator::make($request->all(), [
                        'place' => 'required|integer|unique:tickets|min:1|max:150',
                        'fio' => 'required|max:255',
                        'sell_type' => 'required_with_all:bron,pay',
                        'email' => 'required|email',
                            // 'return_ticket' => '',
            ]);

            if ($validator->fails()) {
                return response()->json([
                            'status' => 'error',
                            'text' => $validator->errors(),
                ]);
            }

// $input = $request->only('place', 'fio', 'sell_type', 'return_ticket');
            $input = $request->only('place', 'fio', 'sell_type', 'email');

            Ticket::insert($input);
            return response()->json([
                        'status' => 'ok',
                        'text' => 'Добавили',
            ]);
        }

        if (1 == 2) {

            if ($request->type == 'pay_ticket') {

                if (1 == 1) {

                    $validator = Validator::make($request->all(), [
                                'place' => 'required|integer|unique:tickets|min:1|max:150',
                                'fio' => 'required|max:255',
                                'sell_type' => 'required_with_all:bron,pay',
                                    // 'return_ticket' => '',
                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                                    'status' => 'error',
                                    'text' => $validator->errors(),
                        ]);
                    }

// $input = $request->only('place', 'fio', 'sell_type', 'return_ticket');
                    $input = $request->only('place', 'fio', 'sell_type');

                    Ticket::insert($input);
                    return response()->json([
                                'status' => 'ok',
                                'text' => 'билет куплен',
                    ]);
                }
            } elseif ($request->type == 'bron_add') {

// второй вариант
                if (1 == 1) {

                    $validator = Validator::make($request->all(), [
                                'place' => 'required|integer|unique:tickets|min:1|max:150',
                                'fio' => 'required|max:255',
                                    // 'sell_type' => 'required',
// 'return_ticket' => '',
                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                                    'status' => 'error',
                                    'text' => $validator->errors(),
                        ]);
                    }

                    $input = $request->only('place', 'fio', 'sell_type', 'return_ticket');
                    $input['sell_type'] = 'bron';

                    Ticket::insert($input);
                    return response()->json([
                                'status' => 'ok',
                                'text' => 'бронь добавлена',
                    ]);
                }

// первый вариант
                if (1 == 2) {

                    if (Ticket::where('place', '=', $request->place)->exists()) {
// user found
                        return response()->json([
                                    'status' => 'error',
                                    'text' => 'место ' . $request->place . ' уже занято',
                        ]);
                    } else {
                        $input = $request->only('place', 'fio', 'sell_type', 'return_ticket');
                        Ticket::insert($input);
                        return response()->json([
                                    'status' => 'ok',
                                    'text' => 'бронь добавлена',
                        ]);
                    }
                }
            }
        }

        return response()->json([
                    'status' => 'error',
                    'text' => __LINE__,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) {

        if (Ticket::where('place', '=', $id)->delete()) {
            return response()->json(['status' => 'ok']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

}
