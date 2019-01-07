<?php
    namespace App\Interfaces;

    /**
     * Interface SendEmailInterface
     *
     * @package App\Interfaces
     */
    interface SendEmailInterface
    {

        /**
         * @param $mail_data
         * @param $extra_data
         *
         * @return mixed
         */
        public function send($send_data, $require_data);

    }