<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class AttendanceController extends Controller
{
    // 打刻処理
    public function startAttendance (Request $request)
        {
            $userId = $request->user()->id; // ログインユーザーID
            $date = Carbon::today()->toDateString();

            // 既に本日の出勤記録が存在するか確認
            $attendance = Attendance::where('user_id', $userId)->where('date', $date)->first();

                if ($attendance) {
                    // 出勤記録が存在する場合はエラーメッセージを返す
                    return redirect()->back()->with('error', '本日の出勤は既に記録されています。');
                }

            // 出勤記録がない場合、新規作成
            Attendance::create([
                'user_id' => $userId,
                'date' => $date,
                'start_time' => Carbon::now(),
            ]);

            $currentStatus = Session::get('status', 'none');
            $action = $request->input('action');

            // 現在の状態とアクションに基づいて状態を更新
            switch ($action) {
                case 'start':
                    $newStatus = 'working';
                    break;
                    default:
                        $newStatus = 'none';
            };
            // ... (省略)

            // 新しい状態をセッションに保存
            Session::put('status', $newStatus);
            return redirect()->back()->with('success', '出勤を記録しました。');
        }

    public function endAttendance (Request $request)
        {
            $userId = $request->user()->id;
            $date = Carbon::today()->toDateString();

            $attendance = Attendance::where('user_id', $userId)->where('date', $date)->first();

                if (!$attendance) {
                    return redirect()->back()->with('error', '出勤記録が存在しません。');
                }

                if ($attendance->end_time) {
                    return redirect()->back()->with('error', '退勤は既に記録されています。');
                }

            $attendance->update(['end_time' => Carbon::now()]);

            $currentStatus = Session::get('status', 'none');
            $action = $request->input('action');

            // 現在の状態とアクションに基づいて状態を更新
            switch ($action) {
                    case 'end':
                        $newStatus = 'none';
                        break;
                    default:
                        $newStatus = 'none';
            };
            // ... (省略)

            // 新しい状態をセッションに保存
            Session::put('status', $newStatus);

            return redirect()->back()->with('success', '退勤を記録しました。');
        }

    public function pagination(Request $request)
        {
            // 表示する日付を取得（指定がない場合は今日の日付）
            $date = $request->input('date', date('Y-m-d'));
            // 指定された日付の勤怠データを取得
            $attendances = Attendance::with('user', 'rests')
                ->where('date', $date) // `date` カラムでフィルタリング
                // ->whereDate('start_time', $date) // 勤務開始時刻でフィルタリング
                ->paginate(5)->appends(['sort' => 'desc'])
                ->appends(['date' => $date]); // クエリパラメータを追加

                foreach ($attendances as $attendance) {
                    // 休憩時間を計算
                    $totalRestTime = DB::table('rests')
                        ->where('attendance_id', $attendance->id)
                        ->sum(DB::raw('TIMESTAMPDIFF(MINUTE, start_time, end_time)'));

                    $attendance->total_rest_time = $totalRestTime;

                        if ($attendance->start_time && $attendance->end_time) {
                            $startTime = \Carbon\Carbon::parse($attendance->start_time);
                            $endTime = \Carbon\Carbon::parse($attendance->end_time);

                            // 勤務時間（分）
                            $totalWorkingMinutes = $endTime->diffInMinutes($startTime);
                            // 休憩時間を引く
                            $workingMinutes = $totalWorkingMinutes - $totalRestTime;
                            // 時間と分にフォーマット（例: 8時間30分）
                            $hours = floor($workingMinutes / 60);
                            $minutes = $workingMinutes % 60;
                            $attendance->working_hours = sprintf('%d時間%d分', $hours, $minutes);
                        } else {
                            $attendance->working_hours = '-'; // 開始または終了時刻がない場合
                        }
                }
            return view('attendance', compact('attendances','date'));
        }

}