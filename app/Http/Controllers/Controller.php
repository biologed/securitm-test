<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class Controller implements ControllerInterface
{
    protected ?Authenticatable $user;
    protected array $mergeData;
    public function __construct()
    {
        $this->user = Auth::user();
        $this->mergeData = [];
    }
    public function index(): View
    {
        return view('index');
    }
}
