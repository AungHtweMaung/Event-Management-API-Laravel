<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        return AttendanceResource::collection($event->attendances()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        // event နဲ့ attendance သည် scoped လုပ်ထားလို့ရော၊ Because of Each attendance is a part of each specific event.
        // event ကနေပဲ တစ်ဆင့် create လုပ်ခိုင်းတာ
        // attendances ထဲမှာ event_id ရော auto ထည့်ပေးသွားတယ်
        $attendance = $event->attendances()->create([
            'user_id' => 1,
        ]);

        return new AttendanceResource($attendance);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendance $attendance)
    {
        return new AttendanceResource($attendance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event, Attendance $attendance)
    {
        $attendance->update([
            'user_id' => $request->user_id,
        ]);

        return new AttendanceResource($attendance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendance $attendance)
    {
        $attendance->delete();
        return response(status:204);
    }
}
