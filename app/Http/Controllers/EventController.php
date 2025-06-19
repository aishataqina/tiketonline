<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Filter berdasarkan kategori
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $events = $query->where('status', 'active')
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->paginate(12);

        $categories = Category::withCount(['events' => function ($query) {
            $query->where('status', 'active')
                ->where('event_date', '>=', now());
        }])->get();

        return view('events.index', compact('events', 'categories'));
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }
}
