<?php

namespace App\Http\Controllers;

use App\Models\hafz;
use App\Models\salf;
use App\Models\shift;
use App\Models\disemp;
use App\Models\vacation;
use App\Models\tlbplease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Events\NotificationSent;

class show_notifyController extends Controller
{
    public function show($id, $type)
    {
        // dd(77);

        // if($type == "tlpfree" || $type == "tlpsalf" ||$type == "tlpplz" || $type == "tlpchange_shift"){
        //     $notifyID= DB::table("notifications")->where("data->id",$id)->pluck("id");
        //     DB::table("notifications")->where("id",$notifyID)->update(["read_at"=>now()]);
        //  }

        //  if($type == "dis" || $type == "hafz" ){
        //     $notifyID= DB::table("notifications")->where("data->id",$id)->pluck("id");
        //     DB::table("notifications")->where("id",$notifyID)->update(["read_at"=>now()]);
        //  }



        if ($type == "tlpfree") {

            ($emp = tlbplease::find($id));
            return view("notify.tlp_notify_free", compact("emp"));
            //
        } elseif ($type == "tlpplz") {

            ($emp = tlbplease::find($id));
            return view("notify.tlp_notify_plz", compact("emp"));
        } elseif ($type == "tlpchange_shift") {

            ($emp = tlbplease::find($id));
            $shifts = shift::get();
            return view("notify.tlp_notify_ch_shift", compact("emp", "shifts"));
        } elseif ($type == "tlpsalf") {

            ($emp = tlbplease::find($id));
            return view("notify.tlp_notify_salf", compact("emp"));
        }

        if ($type == "dis") {

            ($emp = disemp::find($id));
            return view("notify.dis_emp", compact("emp"));
            //
        }

        if ($type == "hafz") {

            ($emp = hafz::find($id));
            return view("notify.hafz_notify", compact("emp"));
            //
        }



/// ok / no // emp




        if ($type == "ok_salf" || $type == "no_salf") {

            ($notifyID = DB::table("notifications")->where("data->id", $id)->first()->id);
            $idtld = DB::table("notifications")->where("id", $notifyID)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.id')) as extracted_id")
            ->value('extracted_id');
            // dd($idtld);
            $emp = tlbplease::where("id", $idtld)->first();
            //    dd($emp);
            return view("notify.ok_no_salf", compact("emp"));
        }

        if ($type == "ok_plz" || $type == "no_plz") {
            ($notifyID = DB::table("notifications")->where("data->id", $id)->first()->id);
            $idtld = DB::table("notifications")->where("id", $notifyID)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.id')) as extracted_id")
            ->value('extracted_id');
            // dd($idtld);
            $emp = tlbplease::where("id", $idtld)->first();
            //    dd($emp);

            return view("notify.ok_no_plz", compact("emp"));
        }

        if ($type == "ok_shift" || $type == "no_shift") {
            ($notifyID = DB::table("notifications")->where("data->id", $id)->first()->id);
            $idtld = DB::table("notifications")->where("id", $notifyID)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.id')) as extracted_id")
            ->value('extracted_id');
            // dd($idtld);
            $emp = tlbplease::where("id", $idtld)->first();
            //    dd($emp);

            return view("notify.ok_no_shift", compact("emp"));
        }

        if ($type == "ok_free" || $type == "no_free") {
            ($notifyID = DB::table("notifications")->where("data->id", $id)->first()->id);
            $idtld = DB::table("notifications")->where("id", $notifyID)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.id')) as extracted_id")
            ->value('extracted_id');
            // dd($idtld);
            $emp = tlbplease::where("id", $idtld)->first();
            //    dd($emp);

            return view("notify.ok_no_free", compact("emp"));
        }

    }
    public function getLatestNotifications()
    {
        $unreadCount = auth()->user()->unreadNotifications->count();
        $notifications = auth()->user()->unreadNotifications->map(function ($notification) {
            return [
                'id' => $notification->data['id'],
                'type' => $notification->data['type'],
                'user' => $notification->data['user'],
                'masg' => $notification->data['masg'],
                'created_at' => $notification->created_at->diffForHumans(),
                'url' => route('show.talb', ['id' => $notification->data['id'], 'type' => $notification->data['type']])
            ];
        });

        return response()->json([
            'unreadCount' => $unreadCount,
            'notifications' => $notifications
        ]);
    }
}
