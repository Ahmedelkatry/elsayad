<?php

namespace App\Http\Controllers;

use App\Models\section;
use Illuminate\Http\Request;
use App\Http\Middleware\ahmed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;

class SectionController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = section::with('managers')->get();
        $employees = \App\Models\employees::get();
        return view("sections.sections", compact("sections", "employees"));
    }

    public function assignManagers(Request $request, $id)
    {
        $section = section::findOrFail($id);
        $section->managers()->sync($request->employee_id ?? []);

        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'رئيس قسم']);
        $owner = \Spatie\Permission\Models\Role::where('name', 'owner')->first();
        if ($owner && $role->permissions->count() == 0) {
            $role->syncPermissions($owner->permissions);
        }

        foreach ($request->employee_id ?? [] as $emp_id) {
            $emp = \App\Models\employees::find($emp_id);
            if ($emp) {
                $user = \App\Models\User::where('num', $emp->num)->first();
                if ($user) {
                    $user->assignRole($role);
                    
                    $roles_name = $user->roles_name ?? [];
                    if (!in_array('رئيس قسم', $roles_name)) {
                        $roles_name[] = 'رئيس قسم';
                        $user->roles_name = $roles_name;
                        $user->save();
                    }
                }
            }
        }

        session()->flash('edit', 'تم تعيين رؤساء القسم بنجاح و إعطائهم الصلاحيات');
        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required"
        ]);

        $data = $request->all();
        section::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(section $section)
    {
        //
    }
    public static function middleware()
    {
        return [
            ahmed::class
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {


        $request->validate([
            "name" => "required"
        ]);
        $data = $request->all();
        section::find($id)->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        section::find($id)->delete();
        return back();
    }

    public function MarkAsRead_all (Request $request)
    {

        $userUnreadNotification= Auth::user()->unreadNotifications;

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }


    }
    public function bulk_delete(Request $request)
    {
        if ($request->ids) {
            section::whereIn('id', $request->ids)->delete();
            return back()->with('delete', 'تم حذف الأقسام المختارة بنجاح');
        }
        return back()->with('error', 'يرجى اختيار أقسام للحذف');
    }
}
