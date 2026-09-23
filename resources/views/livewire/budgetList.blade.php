<?php

use Livewire\Component;
use App\Models\Budget;

new class extends Component
{
    public $selectMonth;
    public $selectYear;
    public $showCreateModel= false;

    public function mount(){
        $this->selectMonth = now()->month;
        $this->selectYear =now()->year;
    }

    public function budget(){
        return Budget::with('category')
        ->where('user_id',auth()->user()->id)
        ->where('month', $this->selectMonth)
        ->where('year', $this->selectYear)
        ->get()
        ->map(function($budget){
            $budget->spent = $this->getSpentAmount();
            $budget->remaining = $this->remainingAmount();
            $budget->percentage = $this->getPercentageUsed();
            $budget->is_over = $this ->isOverDue();
        });
    }
};
?>

<div>
    {{-- Simplicity is the ultimate sophistication. - Leonardo da Vinci --}}
</div>
