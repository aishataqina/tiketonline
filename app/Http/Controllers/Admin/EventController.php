<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.events.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,sold_out',
            'category_id' => 'required|exists:categories,id'
        ]);

        $imagePath = $request->file('image')->store('img-events', 'public');

        // Set status sold_out jika quota 0
        if ($validatedData['quota'] <= 0) {
            $validatedData['status'] = 'sold_out';
        }
        if ($validatedData['event_date'] < now()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['event_date' => 'Tanggal event tidak boleh kurang dari hari ini']);
        }

        Event::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'image' => $imagePath,
            'event_date' => $validatedData['event_date'],
            'location' => $validatedData['location'],
            'price' => $validatedData['price'],
            'quota' => $validatedData['quota'],
            'remaining_quota' => $validatedData['quota'],
            'status' => $validatedData['status'],
            'category_id' => $validatedData['category_id']
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,sold_out',
            'category_id' => 'required|exists:categories,id'
        ]);

        $updateData = [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'event_date' => $validatedData['event_date'],
            'location' => $validatedData['location'],
            'price' => $validatedData['price'],
            'quota' => $validatedData['quota'],
            'status' => $validatedData['status'],
            'category_id' => $validatedData['category_id']
        ];

        // Update remaining_quota jika quota berubah
        if ($event->quota !== $validatedData['quota']) {
            $quotaDiff = $validatedData['quota'] - $event->quota;
            $updateData['remaining_quota'] = $event->remaining_quota + $quotaDiff;
        }

        // Set status sold_out jika remaining_quota 0
        if ($updateData['remaining_quota'] <= 0) {
            $updateData['status'] = 'sold_out';
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $imagePath = $request->file('image')->store('img-events', 'public');
            $updateData['image'] = $imagePath;
        }

        $event->update($updateData);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Cek apakah event memiliki pesanan
        if ($event->orders()->exists()) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Event tidak dapat dihapus karena masih memiliki pesanan aktif.');
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
