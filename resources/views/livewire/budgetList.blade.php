<?php

use Livewire\Component;
use App\Models\Budget;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Illuminate\Support\Carbon;
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

    #[Computed]
    public function totalBudget(){
        return $this->budgets->sum('amount');
    }

    #[Computed]
    public function totalSpent(){
        return $this->budgets->sum('spent');
    }

    #[Computed]
    public function totalRemaing(){
        return $this->budgets->sum('remaing');
    }

    #[Computed]
    public function overallPercentage(){
        if($this->totalBudget == 0){
            return 0;
        }
        return round(($this->totalSpent/$this->totalBudget)*100, 1);
    }

    #[Computed]
    public function categories(){
        return Category::where('user)id', Auth::user()->id)
                        ->orderBy('name')
                        ->get();
    }

    public function previousMonth(){
        $date = Carbon::create($this->selectYear, $this->selectMonth,1)->subMonth();

        $this->selectMonth =$date->month;
        $this->selectYear = $date->year;
    }

    public function nextMonth(){
        $date = Carbon::create($this->selectYear, $this->selectMonth, 1)->addMonth();

        $this->selectMonth = $date->month;
        $this->selectYear = $date->year;
    }

    
}
?>

<div>
    {{-- Simplicity is the ultimate sophistication. - Leonardo da Vinci --}}
</div>
