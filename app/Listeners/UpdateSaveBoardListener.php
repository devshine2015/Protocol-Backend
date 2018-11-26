<?php

namespace App\Listeners;

use App\Events\UpdateSaveBoardEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\Element;

class UpdateSaveBoardListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Element $element)
    {
        $this->element = $element;
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(UpdateSaveBoardEvent $event)
    {
        $elementData = $event->elementData;
        if ($elementData) {
            if ($elementData['is_note'] == 1) {
                $checkElement = $this->element->where('id',$elementData['target'])->first();
                if ($checkElement) {
                    $checkElement->update(["saveBoard"=>0]);
                }
            }else{
                $checkFromElement = $this->element->where('id',$elementData['from'])->first();
                $checkToElement = $this->element->where('id',$elementData['to'])->first();
                if ($checkFromElement) {
                    $checkFromElement->update(["saveBoard"=>0]);
                }
                if ($checkToElement) {
                    $checkToElement->update(["saveBoard"=>0]);
                }
            }
        }
        // 0 = bridges, 1 = notes , 3 = bridge like , 4 = listner
        
    }
}
