<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

final class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard');
    }
}
