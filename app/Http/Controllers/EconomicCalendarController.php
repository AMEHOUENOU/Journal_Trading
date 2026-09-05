<?php

namespace App\Http\Controllers;

use App\Services\EconomicCalendarService;

class EconomicCalendarController extends Controller
{
    public function index(EconomicCalendarService $calendar)
    {
        $events = $calendar->getEvents();
        return view('calendar.index', compact('events'));
    }
}