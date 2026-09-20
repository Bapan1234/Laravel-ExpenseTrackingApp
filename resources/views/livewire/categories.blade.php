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

    protected function rules(){
        return [
            'name'=>'required|string|max:255|unique:categories,name,'.($this->editingId?:'null') .',id,user_id,'. auth()->id(),
            'color'=>'required|string',
            'icon'=>'nullable|string|max:255'
        ];
    }

    protected $messages = [
        'name.required'=>'Please Enter Categories Name',
        'name.unique'=>'Categories Name already Save',
        'color.required' =>'select the color'
    ];

    //use the computed properties
    #[Computed]
    public function categories(){
        return Category::withCount('expenses')
        ->where('user_id',auth()->user()->id)
        ->OrderBy('name')
        ->get();
    }

    public function save(){
        $this->validate();

        Category::create([
            'user_id'=>auth()->id(),
            'name'=>$this->name,
            'color'=>$this->color,
            'icon'=>$this->icon,
        ]);

        session()->flash('message','Categories Created Successfuly');

        $this->reset(['name','color','icon','editingId','isEditing']);
    }

    public function edit($categoryId){
       $category = Category::findOrFail($categoryId);

       if($category->user_id1==auth()->id()){
        abort(403);
       }

       $this->editingId = $category->id;
       $this->name = $category->name;
       $this->color = $category->color;
       $this->icon = $category->icon;
       $this->isEditing = true;

    }

    public function cancelEdit(){
        $this->reset(['name','color','icon','editingId', 'isEditing']);
        $this->color ="#3B82F5";
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
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show text-black" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
                                class="flex-1 px-4 py-2.5 bg-[#10b981] hover:bg-emerald-600 text-white text-sm font-medium rounded-xl transition" style="background-color: #10b981;">
                                Update
                            </button>
                        </div>
                    @else
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-[#10b981] hover:bg-emerald-600 text-white text-sm font-medium rounded-xl transition shadow-sm" style="background-color: #10b981;">
                            Create
                        </button>
                    @endif
                </div>

            </form>
        </div>

        <!-- Category List Table Area (Remaining 2/3 Width) -->
        <div style="width: 66.6666%; flex-grow: 1;">
                <!-- Flash Message -->
                @if (session()->has('message'))
                    <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-gray-800 dark:text-green-400" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-400 mb-6">Your Categories</h2>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse ($this->categories() as $category)
                        <div wire:key="category-{{ $category->id }}" style="background-color: {{ $category->color }}"
                            class="aspect-square bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-4 flex flex-col justify-between items-center text-center transition hover:shadow-lg">

                            <!-- Info -->
                            <div class="flex-1 flex flex-col items-center justify-center">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-1">
                                    {{ $category->name }}
                                </h3>
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                    {{ $category->expenses_count }} {{ Str::plural('Expense', $category->expenses_count) }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 w-full pt-3 border-t border-gray-100 dark:border-gray-700">
                                <!-- Trigger Edit Event/Modal -->
                                <button wire:click="edit({{ $category->id }})"
                                        class="flex-1 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-md transition">
                                    Edit
                                </button>

                                <!-- Direct Livewire Delete -->
                                <button wire:click="deleteCategory({{ $category->id }})"
                                        wire:confirm="Are you sure you want to delete {{ $category->name }}?"
                                        class="flex-1 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-md transition">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-10">
                            No categories found.
                        </div>
                    @endforelse
                </div>
        </div>

    </div>
</div>

