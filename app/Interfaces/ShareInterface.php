<?php
    namespace App\Interfaces;

    /**
     * Interface SendEmailInterface
     *
     * @package App\Interfaces
     */
    interface ShareInterface
    {

        public function shareBridge($bridge_id);

        public function shareNote($note_id);

        public function shareElement($element_id);

        public function shareList($list_id);

    }