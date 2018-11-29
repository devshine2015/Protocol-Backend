<?php

    namespace app\Repository;


    use App\Interfaces\SendEmailInterface;
    use Mail;
    use App\Jobs\SendVerificationEmail;

    /**
     * Class SendEmailRepository
     *
     * @package app\Repository
     */
    class SendEmailRepository implements SendEmailInterface
    {
        /**
         * SendEmailRepository constructor.
         */
        public function __construct()
        {
        }

        /**
         * @param $require_data
         * @param $send_data
         *
         * @return mixed
         */
        public function send($send_data,$require_data)
        {

             Mail::send($send_data['view'], ['data'=>$require_data,'emailData'=>$send_data], function($message) use ($send_data, $require_data) {

                $message->to($send_data['to'])->subject($send_data['subject']);
            });
            return Mail::failures();
        }

    }