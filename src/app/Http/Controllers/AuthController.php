<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
   // ホーム画面表示
    public function index()
    {
    // セッションから現在の状態を取得
    $status = Session::get('status', 'none');

    // 各ボタンの活性化/非活性化を決定
    $canStart = $status === 'none' || Carbon::now()->hour === 0;
    $canEnd = $status === 'working';
    $canStartRest = $status === 'working';
    $canEndRest = $status === 'resting';

    return view('index', compact('canStart', 'canEnd', 'canStartRest', 'canEndRest'));
    }
}
