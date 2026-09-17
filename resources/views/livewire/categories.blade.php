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

<div class="w-full space-y-6 text-gray-900">

    <!-- Header Banner -->
    <div class="w-full rounded-2xl p-6 text-white shadow-sm" style="background-color: #10b981;">
        <h1 class="text-3xl font-bold tracking-tight">Categories</h1>
        <p class="text-emerald-100 mt-1 text-sm">Organize your expenses with custom categories</p>
    </div>

    <!-- Main Grid Section -->
    <div style="display: flex; gap: 1.5rem; align-items: flex-start; width: 100%;">

        <!-- Form Div (Exactly 1/3 Width) -->
        <div style="width: 33.3333%; min-width: 320px; flex-shrink: 0;" class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">

            <h2 class="text-base font-semibold text-gray-900 mb-5 text-black">
                {{ $isEditing ? 'Edit Category' : 'Create Category' }}
            </h2>

            <form wire:submit="save" style="display: flex; flex-direction: column; gap: 1.25rem;">

                <!-- Category Name Field -->
                <div>
                    <label for="name" class="block text-xs font-medium text-gray-600 mb-1.5">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        id="name"
                        wire:model.live="name"
                        placeholder="e.g., Food & Dining"
                        class="w-full px-3 py-2 text-sm border text-black border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('name') border-red-500 @enderror">

                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color Picker Grid -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2">
                        Color <span class="text-red-500">*</span>
                    </label>

                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($colors as $colorOption)
                            <button type="button"
                                wire:click="$set('color', '{{ $colorOption }}')"
                                class="rounded-lg transition-transform hover:scale-105 focus:outline-none flex items-center justify-center"
                                style="width: 2rem; height: 2rem; background-color: {{ $colorOption }}; {{ $color === $colorOption ? 'outline: 2px solid #10b981; outline-offset: 2px;' : '' }}">
                            </button>
                        @endforeach
                    </div>

                    @error('color')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Container -->
                <div class="p-3 bg-gray-50/80 rounded-xl border border-dashed border-gray-200">
                    <p class="text-xs text-gray-500 mb-2">Preview:</p>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium"
                        style="background-color: {{ $color }}20; color: {{ $color }};">
                        <span class="w-2 h-2 rounded-full" style="background-color: {{ $color }};"></span>
                        {{ $name ?: 'Category Name' }}
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    @if($isEditing)
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="button"
                                wire:click="cancelEdit"
                                class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-[#10b981] hover:bg-emerald-600 text-white text-sm font-medium rounded-xl transition">
                                Update
                            </button>
                        </div>
                    @else
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-[#10b981] hover:bg-emerald-600 text-white text-sm font-medium rounded-xl transition shadow-sm">
                            Create
                        </button>
                    @endif
                </div>

            </form>
        </div>

        <!-- Category List Table Area (Remaining 2/3 Width) -->
        <div style="width: 66.6666%; flex-grow: 1;">
            <!-- Categories list content goes here -->
        </div>

    </div>
</div>

