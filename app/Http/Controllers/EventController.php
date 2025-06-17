<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', auth()->id())->get();
        return view('calendar.layout', compact('events'));  
    }

    public function create()
    {
        return view('calendar.layout');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'all_day' => 'boolean',
            'description' => 'nullable|string'
        ]);

        $validated['user_id'] = auth()->id();

        $event = Event::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Sự kiện đã được tạo thành công', 'event' => $event], 201);
        }

        return redirect()->route('calendar.layout')->with('success', 'Sự kiện đã được tạo thành công.');
    }



    public function show($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }

    public function edit(Event $event)
    {
        return view('calendar.layout', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'all_day' => 'boolean',
            'description' => 'nullable|string'
        ]);

        $event->update($request->all());

        return redirect()->route('calendar.layout')->with('success', 'Sự kiện đã được cập nhật.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Đã xóa sự kiện']);
        }

        return redirect()->route('calendar.layout')->with('success', 'Sự kiện đã được xóa.');
    }
}
