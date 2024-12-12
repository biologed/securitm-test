<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

interface ControllerInterface
{
    public function index(): View;
}
