namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class CategorySearch extends Component
{
use WithPagination;

public $search = '';
public $sortField = 'name';
public $sortDirection = 'asc';

public function sortBy($field)
{
if ($this->sortField === $field) {
$this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
} else {
$this->sortField = $field;
$this->sortDirection = 'asc';
}
}

public function updatingSearch()
{
$this->resetPage();
}

public function render()
{
return view('livewire.category-search', [
'categories' => Category::where(function($query) {
$query->where('name', 'like', '%'.$this->search.'%')
->orWhere('description', 'like', '%'.$this->search.'%');
})
->orderBy($this->sortField, $this->sortDirection)
->paginate(10)
]);
}
}