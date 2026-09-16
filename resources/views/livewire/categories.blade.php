<?php
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    public $name="";
    public $color ="#3B82F5";
    public $icon ="";
    public $editingId =null;
    public $isEditing=false;
    public $colors=[
        "#EF4444", // RED
        "#F97316", // ORANGE
        "#FBBF24", // AMBER
        "#EAB308", // YELLOW
        "#84CC16", // LIME
        "#22C55E", // GREEN
        "#10B981", // EMERALD
        "#14B8A6", // TEAL
        "#06B6D4", // CYAN
        "#0EA5E9", // SKY
        "#3B82F6", // BLUE
        "#6366F1", // INDIGO
        "#8B5CF6", // VIOLET
        "#A855F7", // PURPLE
        "#D946EF", // FUCHSIA
        "#EC4899", // PINK
        "#F43F5E", // ROSE
        "#6B7280", // GRAY
        "#737373", // NEUTRAL
        "#78716C", // STONE
    ];

    //use the computed properties
    #[Computed]
    public function categories(){
        return Category::withCount('exoenses')
        ->where('user_id',auth()->user()->id)
        ->OrderBy('name')
        ->get();
    }
}
?>

<div >

</div>
