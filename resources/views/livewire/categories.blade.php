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

<div class="min-h-screen bg-gray-50 dark:bg-natural-900">
    <div class="bg-gradient-to-r from-green-6oo to-emerald-600 shadow-lg">
        <div class="max-w-7x1 mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div>
                <h1 class="text-3x1 font-bold text-white">Categories</h1>
                <p class="text-green-100 mt-1">Organize your expense with custom categories</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Create/Edit Category Form --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">
                {{ $isEditing ? 'Edit Category' : 'Create Category' }}
            </h3>

            <form wire:submit="save" class="space-y-4">
                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        id="name"
                        wire:model="name"
                        placeholder="e.g., Food & Dining"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200">
                        {{ $isEditing ? 'Update Category' : 'Create Category' }}
                    </button>

                    @if($isEditing)
                        <button type="button" wire:click="resetForm" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition duration-200">
                            Cancel
                        </button>
                    @endif
                </div>
                <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Color <span class="text-red-500">*</span>
    </label>

    <div class="grid grid-cols-6 gap-2">
        @foreach($colors as $colorOption)
            <button type="button"
                wire:click="$set('color', '{{ $colorOption }}')"
                class="w-10 h-10 rounded-lg transition transform hover:scale-110 focus:outline-none {{ $color === $colorOption ? 'ring-2 ring-offset-2 ring-indigo-500 scale-110' : '' }}"
                style="background-color: {{ $colorOption }};">
            </button>
        @endforeach
    </div>

    @error('color')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
            </form>
        </div>
    </div>
</div>

