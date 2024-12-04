<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Event::query(); // model ကို query စရေးမှာမလို့ ခုလိုရေးလိုက်တာ လိုအပ်တဲ့ query တွေ ထပ်ထပ်ပြီး ရေးထည့်သွားလို့ရတယ်
        $relations = ['user', 'attendances', 'attendances.user'];   // relation ရှိသမျှတွေရေးထားလိုက်တာ

        foreach($relations as $relation) {
           $query->when($this->shouldIncludeRelation($relation), fn($q) => $q->with($relation));
        }

        return EventResource::collection($query->paginate());
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
        // single event ယူတဲ့အချိန် သူနဲ့သက်ဆိုင်တဲ့ relationship data တွေကို တခါတည်းထည့်ပေးလိုက်တာ
        $event->load(['user', 'attendances']);
        return new EventResource($event);
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

    // optional relation load ဖို့အတွက် ရေးလိုက်တာ
    protected function shouldIncludeRelation(string $relation) {
        // query parameter မှာ include ဆိုတဲ့ key နဲ့ပါရင် ပါတဲ့ တန်ဖိုးယူ။ မပါရင် null ယူ
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));
        // include ထဲမှာပါလာတဲ့ relation တွေကိုပဲ load လုပ်စေချင်လို့
        return in_array($relation, $relations);

    }

}
