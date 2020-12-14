<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Program;
use App\Models\Level;

class Dropdown extends Component
{
    public $program;
    public $levels = [];
    public $level;


    public function render()
    {
        if(!empty($this->program)) {
            $this->levels = Level::where('program_id', $this->program)->get();
        }
        return view('livewire.dropdown')
            ->withPrograms(Program::orderBy('name')->get());
    }
}
