<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\ActivityLog;
use Auth;
use Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $statusArr = [
        'active',
        'inactive',
        'finished'
    ];

    public function index()
    {
        $user = auth()->user();

        $schedules = Schedule::select('id','name','status','date_time')->get();
        
        return view('backend.admin.schedule.index', compact('user','schedules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin.schedule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreScheduleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScheduleRequest $request)
    {
        $form = $request->validated();
        $form['status'] = 'inactive';
        $create = Schedule::create($form);
        $user = Auth::User();
        if ($create) {
            ActivityLog::create([
                'type' => 'create-schedule',
                'user_id' => $user->id, // guest middlware
                'assets' => json_encode([
                    'action' => 'created a new schedule',
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'username' => $user->username,
                    'role' => $user->user_type->role
                ])
            ]);
            return redirect('/admin/schedule')->with('success','New schedule created.');
        }
        return redirect('/admin/schedule')->with('error','Something went wrong!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        // return view('backend.admin.schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        $statusArr = $this->statusArr;
        return view('backend.admin.schedule.edit', compact('schedule','statusArr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateScheduleRequest  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());

        return redirect()->route('schedule.index')->with('success','Schedule updated.');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
