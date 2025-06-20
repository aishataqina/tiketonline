<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

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
        return view('livewire.order-search', [
            'orders' => Order::query()
                ->with(['user', 'event'])
                ->when($this->search, function ($query) {
                    $query->where('order_code', 'like', '%' . $this->search . '%');
                })
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->whereHas('user', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        })
                            ->orWhereHas('event', function ($query) {
                                $query->where('title', 'like', '%' . $this->search . '%');
                            })
                            ->orWhere('id', 'like', '%' . $this->search . '%')
                            ->orWhere('order_code', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->status, function ($query) {
                    $query->where('status', $this->status);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
