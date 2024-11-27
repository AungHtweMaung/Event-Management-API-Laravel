<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Event::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]) + ['user_id' => 1];

        return Event::create($data);

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // return response()->json(['event'=>$event, 'status'=>200]);
        return $event;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // sometimes သည် request ထဲမှာ အဲ့ field ပါလာတဲ့အခါ validation စစ်မယ် ။ မပါလာရင် အဲ့ field ကို မစစ်တော့ဘူး

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date',
        ]) + ['user_id' => 1];

        $event->update($data);

        return $event;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {

        $event->delete();
        // It is beeter not to response any body according to the rest api architecture when deleting something.
        return response(status:204);
    }
}
