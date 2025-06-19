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
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        $events = $query->where('status', 'active')
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->paginate(10);

        $categories = Category::all();

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
