@extends('layouts.master')
@section('title')
    Pixel - الاقسام
@endsection
@section('css')
    <style>
        .t {
            border: 1px solid black;
            width: 100%;
        }

        .d {
            border: 1px solid black;
        }

        .h {
            border: 1px solid black;
        }
    </style>
    {{-- <!-- Internal Data table css --> --}}
    {{-- <!---Internal Fileupload css--> --}}
    {{-- <!--- Internal Select2 css--> --}}
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif





    <div class="row">
        <div class="col-xl-12" style="margin-top: -30px;">
            <label class="text-dark" style="font-size:20px"> تقرير البصمه </label>
            <div class="card mg-b-20">
                {{-- {{ date('d-m-Y H:i:s') }} --}}


                <div>
                    <form action="{{ route('emp-import') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="file" name="file" class="form-control-sm ">
                        <button type="submit" class="btn btn-primary btn-sm  ">Done</button>
                    </form>
                </div>
                <br>


                <form class="date" action="{{ route('countr') }}">
                    <div class="row ">
                        <div class="col-md-8  ">
                            @can('استيراد ملف قسم')
                                <select name="name" id="" class="form-control form-control-sm">
                                    <option disabled selected>اختر القسم </option>
                                    @foreach ($sections as $sec)
                                        <option value="{{ $sec->id }}"
                                            {{ request()->name == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                                    @endforeach

                                </select>
                            @endcan
                            <br>
                            @can('بحث القسم')
                                <input type="date" name="searchs" value="" class="form-control form-control-sm "
                                    placeholder="من">
                                <br>
                                <input type="date" name="searche" class="form-control form-control-sm " placeholder="الي">
                            </div>
                            <div class="col">
                                <button type="submit" class=" btn  btn-primary btn-sm"><i class="fa fa-search"></i>
                                    بحث</button>
                            @endcan
                        </div>
                    </div>
                </form>



                {{-- <div class="table-responsive"> --}}
                {{-- <div class="col"> --}}
                {{-- <a class="modal-effect btn btn-primary btn-sm " data-effect="effect-just-me" data-toggle="modal" --}}
                {{-- href="#modaldemo8"> <i class=" fa fa-plus"></i> اضافه قسم</a> --}}
                {{-- </div> --}}
                {{-- <br> --}}
                {{-- <p class="text-danger" style=font-size:20px>  اسم الموظف : </p> --}}
                {{-- @foreach ($counts as $count) --}}
                {{-- <span class="text-danger" style=font-size:20px> {{ $emps }}</span>   --}}
                {{-- @endforeach --}}
                {{-- <br> --}}
                <br>



                {{-- <table class="table table-sm"> --}}
                {{-- <thead> --}}
                {{-- <tr> --}}

                {{-- <th  > التاريخ </th> --}}
                {{-- <th> الدخول الملزم </th> --}}
                {{-- <th> الخروج الملزم </th> --}}
                {{-- <th>الشيفت</th> --}}
                {{-- <th>دخول</th> --}}
                {{-- <th>خروج</th> --}}
                {{-- <th>خصم دخول</th> --}}
                {{-- <th>خصم خروج</th> --}}
                {{-- <th> تاخير</th> --}}
                {{-- <th> عدد ساعات العمل </th>  --}}
                {{-- <th> عدد ساعات ov </th> --}}
                {{-- <th> الغياب </th> --}}
                {{-- </tr> --}}
                {{-- </thead> --}}
                {{-- @foreach ($cos as $count) --}}
                {{-- <tbody> --}}
                {{-- <tr> --}}


                {{-- <td class="{{ $count->updated_at == $count->created_at ? "": "text-warning"}}" >{{ $count->date }} </td> --}}
                {{-- <td>{{ $count->in_duty }}</td> --}}
                {{-- <td>{{ $count->out_duty }}</td> --}}
                {{-- <td>{{ $count->shift }}</td> --}}
                {{-- <td>{{ $count->in }}</td> --}}
                {{-- <td>{{ $count->out }}</td> --}}
                {{-- <td class="text-danger">{{ $count->dis }}   </td> --}}
                {{-- <td class="text-danger"> {{ $count->disout }} </td> --}}
                {{-- <td class="text-danger">{{ $count->early }}</td> --}}

                {{-- <td class="text-success">{{ round($count->dayh) }}</td> --}}
                {{-- <td class="text-success">{{ $count->othours }}</td> --}}
                {{-- <td class="text-danger">{{ $count->absent == true ? 'غياب' : '' }}</td> --}}
                {{-- </tr> --}}
                {{-- </tbody> --}}
                {{-- @endforeach --}}
                {{-- </table> --}}
                {{-- @if (App\models\count::count() > 0) --}}
                {{-- @else --}}
                {{-- لا يوجد بيانات --}}
                {{-- @endif --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}


                {{-- <br> --}}





                {{-- <div class="row" style="margin-top: -30px;"> --}}
                {{-- <div class="col-xl-6" > --}}

                {{-- <label class="text-dark" >تقرير الخصومات خارج البصمه </label> --}}
                {{-- <div class="card mg-b-20"> --}}
                {{-- <div class="card-header pb-0"> --}}
                {{-- <table class="table table-sm"> --}}
                {{-- <thead> --}}
                {{-- <tr> --}}
                {{-- <th scope="col">الخصم</th> --}}
                {{-- <th scope="col">سبب الخصم</th> --}}
                {{-- <th scope="col">التاريخ</th> --}}
                {{-- </tr> --}}
                {{-- </thead> --}}
                {{-- <tbody> --}}
                {{-- @foreach ($disemp as $dise) --}}
                {{-- <tr> --}}
                {{-- <td class="text-danger">{{ $dise->dis }}    جنيه</td> --}}
                {{-- <td>{{ $dise->dec }} </td> --}}
                {{-- <td>{{ $dise->date }}</td> --}}
                {{-- </tr> --}}
                {{-- @endforeach --}}
                {{-- </tbody> --}}
                {{-- </table> --}}
                {{-- @if (App\models\count::count() > 0) --}}
                {{-- @else --}}
                {{-- <p class="text-dark" > لايوجد بيانات </p> --}}
                {{-- @endif --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}


                {{-- <div class="col-xl-6"> --}}
                {{-- <label class="text-dark"  > السلفه </label> --}}
                {{-- <div class="card mg-b-20"> --}}
                {{-- <div class="card-header pb-0"> --}}
                {{-- <table class="table table-sm"> --}}
                {{-- <thead> --}}
                {{-- <tr> --}}
                {{-- <th scope="col">السلفه</th> --}}
                {{-- <th scope="col">سبب السلفه</th> --}}
                {{-- <th scope="col">التاريخ</th> --}}
                {{-- </tr> --}}
                {{-- </thead> --}}
                {{-- <tbody> --}}
                {{-- @foreach ($salfs as $f) --}}
                {{-- <tr> --}}
                {{-- <td class="text-danger">{{ $f->salf }}</td> --}}
                {{-- <td>{{ $f->dec }}   </td> --}}
                {{-- <td>{{ $f->date }}</td> --}}
                {{-- </tr> --}}
                {{-- @endforeach --}}
                {{-- </tbody> --}}
                {{-- </table> --}}
                {{-- @if (App\models\count::count() > 0) --}}
                {{-- @else --}}
                {{-- لا يوجد بيانات --}}
                {{-- @endif --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}


                {{-- <table class="table table-sm"> --}}
                {{-- <tr> --}}
                {{-- @foreach ($go as $o => $a) --}}
                {{-- <td>{{$a[0]["employees"]}}</td> --}}
                {{-- <td>{{$a->sum("absent")}}</td> --}}
                {{-- @endforeach --}}
                {{-- </tr> --}}
                {{-- </table> --}}
                {{--  --}}



                <div class="col-xl-12" style="margin-top: -10px;">

                    <div class="card mg-b-20">
                        <div class="card-header pb-0">

                            <table id="mytable" class="table key-buttons text-md-nowrap myTable mytable">
                                <thead>

                                    <tr>
                                        <th> اسم الموظف </th>
                                        <th> خصم دخول </th>
                                        <th> خصم خروج </th>
                                        <th> عدد ايام الغياب </th>
                                        <th> خصم الغياب </th>
                                        <th>خصم خارج البصمه</th>
                                        <th>خصم سلف</th>
                                        <th> القرض</th>
                                        <th>خصم مواصلات</th>
                                        <th>بدل مواصلات</th>
                                        <th> التامينات</th>
                                        <th> المشتريات</th>
                                        <th> عدد ساعات العمل</th>
                                        <th> المرتب</th>
                                        <th> حساب يوم الموظف</th>
                                        <th> بدل انتظام </th>
                                        <th> عدد ايام الشهر</th>
                                        <th> عدد ايام الاجازه بدون خصم</th>
                                        <th> حضور الاجازه الرسميه</th>
                                        <th> ot اجازه</th>
                                        <th> ot عدد ساعات</th>
                                        <th> ot </th>
                                        <th> الحوافز </th>
                                        <th class="text-success"> المرتب بعد ot </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($go as $o => $a)
                                        <tr>

                                            <td class="text-danger">{{ $a[0]->name }}</td>
                                            <td class="text-danger">{{ round($in = $a->sum('dis'), 2) }}</td>
                                            <td class="text-danger">{{ round($out = $a->sum('disout'), 2) }}</td>
                                            <td class="text-danger">{{ $a->sum('absent') }}</td>

                                            @php $sysemp = App\models\employees::where('name', $o)->first()->sysemp @endphp
                                            <td class="text-danger">
                                                {{ $absentt = round(
                                                    (App\models\shift::where('name', $a[0]->shift)->first()->absent *
                                                        $a->sum('absent') *
                                                        App\models\employees::where('name', $o)->first()->kabd) /
                                                        $sysemp,
                                                    2,
                                                ) }}
                                            </td>

                                            <td class="text-danger">
                                                {{-- @foreach ($disr as $d => $ss) --}}

                                                {{-- <td>{{$disr}}</td> --}}
                                                {{-- @endforeach --}}

                                                <?php $y = ['[', ']']; ?>
                                                {{ $outdis = App\models\disemp::where(
                                                    'employees_id',
                                                    App\models\employees::where('name', $a[0]->name)->first()->id,
                                                )->whereBetween('date', [$res, $ree])->get()->sum('dis') }}
                                            </td>
                                            <td class="text-danger">

                                                {{-- {{$salfdis=  str_replace($y,"", App\models\salf::where("employees_id",App\models\employees::where("name",$o)->first()->id)->pluck("salf"))}} --}}
                                                {{-- {{ $salfdis = collect(App\models\salf::where('employees_id', App\models\employees::where('name', $o)->first()->id)->whereBetween('date', array($res, $ree))->pluck('salf'))->first() }} --}}
                                                {{ $salfdis = App\models\salf::where('employees_id', App\models\employees::where('name', $o)->first()->id)->whereBetween('date', [$res, $ree])->get()->sum('salf') }}
                                                {{-- <br> --}}
                                                {{-- {{$ff=collect($pp)->first() }} --}}
                                            </td>

                                            <td>

                                                {{ $loan = App\models\loan::where('employees_id', App\models\employees::where('name', $o)->first()->id)->where('date_start', '<=', $res)->where('date_end', '>=', $res)->get()->sum('mony_now') }}


                                            </td>




                                            <td>
                                                {{ $dis_transport = App\models\dis_transport::where('employees_id', App\models\employees::where('name', $o)->first()->id)->get()->sum('mony') }}

                                            </td>

                                            <td>
                                                {{ $badel_transport = App\models\badel_transport::where('employees_id', App\models\employees::where('name', $o)->first()->id)->get()->sum('mony') }}

                                            </td>



                                            <td>
                                                {{ $taminat = App\models\taminat::where('employees_id', App\models\employees::where('name', $o)->first()->id)->get()->sum('mony') }}

                                            </td>


                                            <td>
                                                {{ $purchases = App\models\purchases::where(
                                                    'employees_id',
                                                    App\models\employees::where('name', $o)->first()->id,
                                                )->whereBetween('date', [$res, $ree])->get()->sum('mony') }}

                                            </td>

                                            <td class="text-success">
                                                {{ round(
                                                    App\models\count::where('name', $o)->whereBetween('date', [$res, $ree])->sum('dayh') /
                                                        60 /
                                                        60,
                                                ) < 0
                                                    ? round(
                                                            App\models\count::where('name', $o)->whereBetween('date', [$res, $ree])->sum('dayh') /
                                                                60 /
                                                                60,
                                                        ) + 24
                                                    : round(
                                                        App\models\count::where('name', $o)->whereBetween('date', [$res, $ree])->sum('dayh') /
                                                            60 /
                                                            60,
                                                    ) }}


                                            </td>
                                            <td class="text-success">
                                                {{ $kabdd = App\models\employees::where('name', $o)->sum('kabd') }}</td>

                                            <td class="text-success">
                                                {{ $countday = number_format(App\models\employees::where('name', $o)->first()->countday, 2) }}
                                            </td>

                                            @php

                                                $badel_sys = 0;

                                                $empps = App\models\employees::where('name', $o)->first()
                                                    ;

                                                $early_count = App\models\count::where('employees_id', $empps->id)
                                                    ->whereBetween('date', [$res, $ree])
                                                    ->sum('early');
                                                // dd
                                                $early_c =Carbon\Carbon::parse($early_count)->secondsSinceMidnight();

                                                $absent_count = App\models\count::where('employees_id', $empps->id)
                                                    ->whereBetween('date', [$res, $ree])
                                                    ->sum('absent');

                                                $shift_hdays = App\models\employees::find($empps->id)->shift->workh;
                                                //  dd($shift_hdays*60*60*2);

                                                if (
                                                    ($early_c < $shift_hdays * 60 * 60 * 2 && $absent_count == 0) ||
                                                    ($absent_count == 1 && $early_count < $shift_hdays * 60 * 60)
                                                ) {
                                                    // dd("ok");
                                                    $badel_sys = 2 * $empps->kabd;
                                                } else {
                                                    $badel_sys = 0;
                                                    // dd("no");
                                                }
                                            @endphp

                                            <td>
                                                {{$badel_sys}}
                                            </td>

                                            <td class="text-success">
                                                {{ App\models\vacation::where('employees_id', App\models\employees::where('name', $o)->first()->id)->get()->count() }}
                                            </td>
                                            <td>{{ $sysemp }}</td>

                                            <td class="text-info">
                                                {{ App\models\count::where('name', $o)->whereBetween('date', [$res, $ree])->sum('friday') }}
                                            </td>

                                            <td class="text-info">
                                                {{ round(
                                                    ($otfree =
                                                        App\models\count::where('name', $o)->whereBetween('date', [$res, $ree])->sum('friday') *
                                                        //  App\models\shift::where('name', $a[0]->shift)->first()->friday *
                                                        App\models\employees::where('name', $o)->first()->friday *
                                                        App\models\employees::where('name', $o)->first()->kabd) / $sysemp,
                                                    2,
                                                ) }}
                                            </td>

                                            <td class="text-success">
                                                {{ Carbon\Carbon::parse(App\models\count::where('name', $o)->whereBetween('date', [$res, $ree])->sum('othours'))->format('H:i:s') }}
                                            </td>

                                            <td class="text-success">
                                                {{ $ot = round(
                                                    (App\models\count::where('name', $o)->whereBetween('date', [$res, $ree])->sum('othours') /
                                                        60 /
                                                        60) *
                                                        App\models\employees::where('name', $o)->first()->counth *
                                                        App\models\shift::where('name', $a[0]->shift)->first()->ot,
                                                    2,
                                                ) }}
                                            </td>

                                            <td class="text-success">
                                                {{-- {{ round($hafz = collect(App\models\hafz::where('employees_id', App\models\employees::where('name', $o)->first()->id)->whereBetween('date', array($res, $ree))->pluck('hafz'))->first(), 2) }} --}}
                                                {{ $hafz = App\models\hafz::where('employees_id', App\models\employees::where('name', $o)->first()->id)->whereBetween('date', [$res, $ree])->get()->sum('hafz') }}
                                            </td>
                                            <td {{-- {{type($ot)}} --}} class="text-success">
                                                {{ number_format(
                                                    App\models\employees::where('name', $o)->first()->countday * $countdd +
                                                        $hafz +
                                                        $otfree +
                                                        $ot -
                                                        $in -
                                                        $out -
                                                        $absentt -
                                                        $salfdis -
                                                        $outdis -
                                                        $dis_transport -
                                                        $badel_transport -
                                                        $taminat -
                                                        $purchases -
                                                        $loan,
                                                    2,
                                                ) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <h5 style="text-align: left" id="result"> </h5>
                            {{-- @if (App\models\count::count() > 0) --}}
                            {{-- @else --}}
                            {{-- لا يوجد بيانات --}}
                            {{-- @endif --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    </div>
    </div>

@endsection
@section('js')
    <script>
        // function sumColumn() {
        // تحديد الجدول
        // const table = document.getElementsByClassName('mytable');
        const table = document.getElementById('mytable');
        let sum = 0;
        let sums = 0;
        const rows = table.querySelectorAll('tbody tr'); // يحصل على جميع الصفوف داخل tbody
        rows.forEach(row => {
            // جلب قيمة الخلية في العمود الثاني (index 1)
            const value = parseFloat(row.cells[22].textContent.trim());
            if (!isNaN(value)) {
                sum += value;
            }
        });

        // rows.forEach(row => {
        //     // جلب قيمة الخلية في العمود الثاني (index 1)
        //     const value = parseFloat(row.cells[17].textContent.trim());
        //     if (!isNaN(value)) {
        //         sums += value;
        //     }
        // });

        // عرض المجموع
        document.getElementById('result').textContent = sum;
        // document.getElementById('absent').textContent = sums;
        // }
    </script>
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
@endsection
