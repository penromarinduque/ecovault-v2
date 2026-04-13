<?php

namespace App\Livewire\Main;

use App\Models\File;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Validate extends Component
{
    public $barcode_no;
    public $file;

    public function mount($id)
    {
        $this->barcode_no = base64_decode(strtr($id, '-_,', '+/='));
        $this->file = File::where('barcode_no', $this->barcode_no)->first();
    }

    #[Layout('components.layouts.full')]  
    public function render()
    {
        return view('livewire.main.validate');
    }
}
