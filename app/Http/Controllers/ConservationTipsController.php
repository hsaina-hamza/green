<?php

namespace App\Http\Controllers;

use App\Services\ConservationTipsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConservationTipsController extends Controller
{
    protected $tipsService;

    /**
     * Create a new controller instance.
     *
     * @param ConservationTipsService $tipsService
     */
    public function __construct(ConservationTipsService $tipsService)
    {
        $this->tipsService = $tipsService;
    }

    /**
     * Display the conservation tips page.
     *
     * @return \Illuminate\View\View
     */
    public function __invoke()
    {
        $tips = $this->tipsService->getTips(Auth::user()->role);
        return view('conservation-tips-simple', compact('tips'));
    }
}
