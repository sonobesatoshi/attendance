<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Rest;
use App\Models\Attendance;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class RestController extends Controller
{
    public function startRest(Request $request)
    {
        // ログイン中のユーザーの最新のattendanceレコードを取得
        $attendance = auth()->user()->attendances()->latest()->first();

        // 新規休憩レコードを作成
        $rest = Rest::create([
            'attendance_id' => $attendance->id,
            'start_time' => Carbon::now(),
        ]);

        $currentStatus = Session::get('status', 'none');
        $action = $request->input('action');

        // 現在の状態とアクションに基づいて状態を更新
        switch ($action) {
            case 'start_rest':
                $newStatus = 'resting';
                break;
                default:
                    $newStatus = 'none';
        };
        // ... (省略)

        // 新しい状態をセッションに保存
        Session::put('status', $newStatus);

        return redirect()->back()->with('success', '休憩開始を記録しました。');
    }

    public function endRest(Request $request)
        {
            // ログイン中のユーザーの最新のattendanceレコードを取得
            $attendance = auth()->user()->attendances()->latest()->first();

            $lastRest = Rest::where('attendance_id', $attendance->id)
            ->whereNull('end_time')
            ->orderBy('created_at', 'desc')
            ->first();
            if ($lastRest) {
            $lastRest->end_time = now();
            $lastRest->save();
            }

            $currentStatus = Session::get('status', 'none');
                $action = $request->input('action');

                // 現在の状態とアクションに基づいて状態を更新
                switch ($action) {
                    case 'end_rest':
                        $newStatus = 'working';
                        break;
                        default:
                            $newStatus = 'none';
                };
                // ... (省略)

                // 新しい状態をセッションに保存
                Session::put('status', $newStatus);

                return redirect()->back()->with('success', '休憩終了を記録しました。');
        }
}
