<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Model2 extends Component
{
    public $name;
    public $btn_name;
    // public $html;


    public function mount($name,$btn_name){
        $this->name = $name;
        // $this->html = $html;
        $this->btn_name = $btn_name;
    }
    
    public function render()
    {
        return view('livewire.model2');
    }
}
