<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

final class PlaygroundController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Playground', []);
    }

    public function analyze(): void
    {
        //
    }
}
