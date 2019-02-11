<?php

namespace app\Repository;


use App\Bridge;
use App\Element;
use App\Interfaces\ShareInterface;
use App\ListModel;
use App\Message;
use App\Note;

/**
 * Class SendEmailRepository
 *
 * @package app\Repository
 */
class ShareRepository implements ShareInterface
{
    /**
     * SendEmailRepository constructor.
     */
    protected $bridgeModel;
    protected $noteModel;
    protected $elementModel;
    protected $messageModel;
    protected $listModel;
    public function __construct(Bridge $bridgeModel,Note $noteModel,Element $elementModel,Message $messageModel, ListModel $listModel)
    {
        $this->bridgeModel  =   $bridgeModel;
        $this->noteModel    =   $noteModel;
        $this->elementModel =   $elementModel;
        $this->messageModel =   $messageModel;
        $this->listModel    =   $listModel;
    }

    public function shareBridge($bridge_id){
    $shareData = $this->bridgeModel->with('fromElement','toElement')->find($bridge_id);
    if($shareData){
        $shareData->req_type    =  0;
        $shareAdminMessage      = $this->getMessage(1);
        if($shareAdminMessage){
            $shareData->adminMessage = $shareAdminMessage->message;
        }
        return $shareData;
    }
    return false;
    }
    public function shareNote($note_id){
        $shareData = $this->noteModel->with('targetData','relationData')->find($note_id);
        if($shareData){
            $shareData->req_type    =   1;
            $shareAdminMessage      =   $this->getMessage(2);
            if($shareAdminMessage){
                $shareData->adminMessage = $shareAdminMessage->message;
            }
            return $shareData;
        }
        return false;
    }
    public function shareElement($element_id){
        $shareData = $this->elementModel->find($element_id);
        if($shareData){
            $shareData->req_type    =   2;
            $shareAdminMessage      =   $this->getMessage(3);
            if($shareAdminMessage){
                $shareData->adminMessage = $shareAdminMessage->message;
            }
            return $shareData;
        }
        return false;
    }
    public function shareList($list_id){
        $shareData = $this->listModel->with('elementData')->find($list_id);
        if($shareData){
            $shareData->req_type = 3;
            $shareAdminMessage   = $this->getMessage(3);
            if($shareAdminMessage){
                $shareData->adminMessage = $shareAdminMessage->message;
            }
            return $shareData;
        }
        return false;
    }
    private function getMessage($type){
        if($messageData = $this->messageModel->where('message_categories_id',$type)->whereDate('start_date', '<=', date("Y-m-d"))
            ->whereDate('end_date', '>=', date("Y-m-d"))->orderBy('updated_at','desc')->orderBy('created_at','desc')->first()){
            return $messageData;
        }
        return false;
    }

}