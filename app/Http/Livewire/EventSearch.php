namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event;
use Livewire\WithPagination;

class EventSearch extends Component
{
use WithPagination;

public $search = '';
public $sortField = 'created_at';
public $sortDirection = 'desc';

public function sortBy($field)
{
if ($this->sortField === $field) {
$this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
} else {
$this->sortField = $field;
$this->sortDirection = 'asc';
}
}

public function render()
{
return view('livewire.event-search', [
'events' => Event::with('category')
->where('title', 'like', '%'.$this->search.'%')
->orderBy($this->sortField, $this->sortDirection)
->paginate(10)
]);
}
}