<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReminderServiceData extends Controller
{
    public function index() {
        return view('dashboard.reminder_service-data');
    }
}
