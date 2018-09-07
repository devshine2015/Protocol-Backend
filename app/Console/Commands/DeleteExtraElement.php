<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Element;

class DeleteExtraElement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeleteExtraElement:deleteElements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete element with 0 badge';
    protected $model;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Element $element)
    {
        parent::__construct();
        $this->model = $element;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $getElement = $this->model->doesntHave('fromElement')->doesntHave('toElement')->doesntHave('notes')->get();
        if($getElement){
            $getElement->delete();
            return 'element deleted';
        }
        return 'No element found with 0 badges';
    }
}
