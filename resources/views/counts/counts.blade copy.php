@extends('layouts.master')
@section('title')
    Pixel - الاقسام
@endsection
@section('css')
    <style>
        .hid {
            display: none;
        }

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
            <div class="card card-body ">
                {{-- {{ date('d-m-Y H:i:s') }} --}}

                <div>
                    <form action="{{ route('salf_import') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="file" name="file" class="form-control-sm ">
                        <button type="submit" class="btn btn-primary btn-sm  ">Done</button>
                    </form>
                </div>
                <br>


                <form class="date" action="{{ route('counts.index') }}">
                    <div class="row ">
                        <div class="col-md-8  ">

                            @can('استيراد ملف موظف')
                                <select name="name" id="" class="form-control form-control-sm">
                                    <option disabled selected>اختر الموظف </option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}"
                                            {{ request()->name == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                    @endforeach

                                </select>
                            @endcan
                            <br>
                            @can('بحث موظف')
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
            </div>
            <div class="card mg-b-20" id="tabs-style2">

                <div class="text-wrap">
                    <div class="example">
                        <div class="panel panel-primary tabs-style-2">
                            <div class=" tab-menu-heading">
                                <div class="tabs-menu1">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs main-nav-line">
                                        <li><a href="#tab4" class="nav-link active" data-toggle="tab">تقرير البصمه
                                                اليومي</a>
                                        </li>
                                        <li><a href="#tab5" class="nav-link" data-toggle="tab"> تقرير الخصومات والسلفه
                                             </a></li>
                                        {{-- <li><a href="#tab6" class="nav-link" data-toggle="tab">التقرير النهائي</a></li> --}}
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body main-content-body-right border">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab4">

                                        <table class="table table-sm " id='mytable'>
                                            <thead>
                                                <tr>

                                                    <th> التاريخ </th>
                                                    <th> الدخول الملزم </th>
                                                    <th> الخروج الملزم </th>
                                                    <th>الشيفت</th>
                                                    <th>دخول</th>
                                                    <th>خروج</th>
                                                    <th>خصم دخول</th>
                                                    <th>خصم خروج</th>
                                                    <th> تاخير</th>
                                                    <th> اجازه رسميه</th>
                                                    <th> حافز</th>
                                                    <th> عدد ساعات العمل </th>
                                                    <th> عدد ساعات ov </th>
                                                    <th> ot حساب </th>
                                                    <th> الخصم </th>
                                                    <th> سلفه </th>
                                                    <th> الغياب </th>
                                                    <th> حساب الغياب </th>
                                                    <th> ##</th>

                                                </tr>
                                            </thead>
                                            {{-- {{ $i = 0 }} --}}
                                            @php
                                                $t = 1;
                                            @endphp
                                            @php
                                                $sort = $cos->sortBy('date');
                                            @endphp
                                            @foreach ($sort as $count)
                                                <tbody>


                                                    <tr>

                                                        <td>{{ $count->date }} ->
                                                            {{ Carbon\Carbon::parse($count->date)->locale('ar')->dayName }}
                                                        </td>
                                                        <td>{{ $count->in_duty }}</td>
                                                        <td>{{ $count->out_duty }}</td>
                                                        <td>{{ $count->shift }}</td>
                                                        <td>{{ $count->in }}</td>
                                                        <td>{{ $count->out }}</td>
                                                        <td class="text-danger">{{ round($count->dis, 2) }} </td>
                                                        <td class="text-danger"> {{ round($count->disout, 2) }} </td>
                                                        <td class="text-danger">{{ $count->early }}</td>
                                                        <td class="text-success">{{ $count->friday == 1 ? 'حاضر' : '' }}
                                                        </td>



                                                        @php $hafzc=0 @endphp
                                                        @if (App\models\hafz::where("employees_id",$id_emp)->where('date', $count->date)->first('hafz') === null)
                                                            <td>0</td>
                                                        @else
                                                            <td> {{$hafzc= App\models\hafz::where("employees_id",$id_emp)->where('date', $count->date)->first()->hafz }}
                                                            </td>
                                                        @endif




                                                        {{-- <td>{{ $count->ot }}</td> --}}
                                                        {{-- <td class="text-success">{{ $count->dayh }}</td> --}}
                                                        <td class="text-success">
                                                            {{ Carbon\Carbon::parse($count->dayh)->format('H:i:s') }}</td>
                                                        {{-- <td class="text-success">{{  $count->othours }}</td> --}}
                                                        <td class="text-success">
                                                            {{ Carbon\Carbon::parse($count->othours)->format('H:i:s') }}
                                                        </td>

                                                        <td class="text-success">
                                                            {{ $otmin = round(
                                                                (((App\models\shift::where('id', $count->shift_id)->first()->ot * $count->othours) / 60) *
                                                                    $count->employees->counth) /
                                                                    60,
                                                                2,
                                                            ) }}
                                                        </td>

                                                        @php $disc=0 @endphp
                                                        @if (App\models\disemp::where("employees_id",$id_emp)->where('date', $count->date)->first('dis') === null)
                                                            <td>0</td>
                                                        @else
                                                            <td> {{ $disc = App\models\disemp::where("employees_id",$id_emp)->where('date', $count->date)->sum("dis") }}
                                                            </td>
                                                        @endif


                                                        @php $salfc=0 @endphp
                                                        @if (App\models\salf::where("employees_id",$id_emp)->where('date', $count->date)->first('salf') === null)
                                                            <td>0</td>
                                                        @else
                                                            <td> {{$salfc= App\models\salf::where("employees_id",$id_emp)->where('date', $count->date)->sum("salf") }}
                                                            </td>
                                                        @endif





                                                        <td class="text-danger">
                                                            {{ $count->absent == true ? 'غياب' : '' }}
                                                        </td>

                                                        <td class="text-danger">
                                                            {{ $abseent = round(
                                                                $count->absent * $count->employees->countday * App\models\shift::where('id', $count->shift_id)->first()->absent,
                                                                2,
                                                            ) }}
                                                        </td>







                                                        <td>
                                                            {{-- @if ($count->absent == 1)
                                                            {{ round($count->dis - $count->disout- $salfc  - $abseent - $disc + $hafzc + $otmin
                                                         //   + $count->employees->countday
                                                             , 2) }}
                                                            @else --}}

                                                            {{ round(
                                                                $count->dis - $count->disout - $abseent - $disc - $salfc + $hafzc + $otmin
                                                              //     + $count->employees->countday

                                                                ,2
                                                            ) }}
                                                              {{-- @endif --}}
                                                        </td>

                                                    </tr>
                                                </tbody>
                                            @endforeach

                                        </table>
                                        @if (App\models\count::all()->count() > 0)
                                        @else
                                            لا يوجد بيانات
                                        @endif

                                        <h5 style="text-align: left" id="result"> </h5>

                                        {{-- <button onclick="sumColumn()">جمع العمود</button> --}}
                                        {{-- <p id="results"></p> --}}


                                        <hr>
                                        <hr>

                                        <table class="table table-sm" >
                                            <thead>
                                                <tr>
                                                    <th> خصم دخول </th>
                                                    <th> خصم خروج </th>
                                                    <th> عدد ايام الغياب </th>
                                                    <th> خصم الغياب </th>
                                                    <th>خصم خارج البصمه</th>
                                                    <th>خصم سلف</th>
                                                    <th> القرض </th>
                                                    <th>خصم المواصلات </th>
                                                    <th>بدل المواصلات </th>
                                                    <th> التامينات </th>
                                                    <th> المشتريات </th>
                                                    <th> اجمالي الخصومات</th>
                                                    <th> عدد ساعات العمل</th>
                                                    <th> المرتب</th>
                                                    <th> يوم الموظف</th>
                                                    <th>  عدد الايام الشهر</th>
                                                    <th>  عدد ايام الاجازه بدون خصم</th>
                                                    <th> حضور الاجازه الرسميه </th>
                                                    <th> ot اجازه </th>
                                                    <th> عدد ساعات ot </th>
                                                    <th> ot </th>
                                                    <th> الحوافز </th>
                                                    <th> المرتب بعد ot </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-danger">{{ round($disin, 2) }}</td>
                                                    <td class="text-danger">{{ round($disout, 2) }}</td>
                                                    <td class="text-danger">{{ $absent }}</td>
                                                    <td class="text-danger">{{ number_format($disabsent, 2) }}
                                                        {{-- <td class="text-danger" id="absent" > --}}
                                                    </td>
                                                    <td class="text-danger">{{ $disdis }}</td>
                                                    <td class="text-danger">{{ number_format($dissalf,2) }}</td>
                                                    <td class="text-danger">{{ number_format($loan,2) }}</td>
                                                    <td class="text-danger">{{ number_format($dis_trans) }}</td>
                                                    <td class="text-danger">{{ number_format($badel_trans) }}</td>
                                                    <td class="text-danger">{{ number_format($taminat) }}</td>
                                                    <td class="text-danger">{{ number_format($purchases) }}</td>
                                                    <td class="text-danger">{{ number_format($afterdis
                                                       // $dissalf + $disin + $disout + $disabsent + $disdis + $dis_trans
                                                   //  + $loan+ $badel_trans
                                                     ,2)}}</td>
                                                    {{-- <td class="text-success">{{ round($dayss) }}</td> --}}
                                                    <td class="text-success">
                                                        {{ Carbon\Carbon::parse($dayss < 0 ? $dayss + 24 : $dayss)->format('H:i:s') }}
                                                    </td>
                                                    <td class="text-success">{{ $empkabd }}</td>
                                                    <td class="text-success">{{ round($countday,2) }}</td>
                                                    <td class="text-success">{{ $sysemp }}</td>
                                                    <td class="text-success">{{ $count_vacation }}
                                                    </td>
                                                    <td class="text-info">{{ $fri }}</td>
                                                    <td class="text-info">{{ round($friday) }}</td>
                                                    {{-- <td class="text-success">{{ round($othours) }}</td> --}}
                                                    <td class="text-success">
                                                        {{ Carbon\Carbon::parse($othours)->format('H:i:s') }}
                                                    </td>
                                                    <td class="text-success">{{ number_format($overtime, 2) }}
                                                    </td>
                                                    <td class="text-success">{{ number_format($hafzs, 2) }}
                                                    </td>
                                                    {{-- <td class="text-success">{{ number_format($afterot, 2) }}
                                                    </td> --}}
                                                </tr>
                                            </tbody>
                                           <p class="text-success">{{ number_format($afterot, 2)}}</p>
                                        </table>
                                        @if (App\models\count::count() > 0)
                                        @else
                                            لا يوجد بيانات
                                        @endif




                                    </div>






                                    <div class="tab-pane" id="tab5">


                                        <div class="row" style="margin-top: -30px;">
                                            <div class="col-xl-6">
                                                <label class="text-dark">تقرير الخصومات خارج البصمه </label>
                                                <div class="card mg-b-20">
                                                    <div class="card-header pb-0">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">الخصم</th>
                                                                    <th scope="col">سبب الخصم</th>
                                                                    <th scope="col">التاريخ</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($disemp as $dise)
                                                                    <tr>
                                                                        <td class="text-danger">{{ $dise->dis }} جنيه
                                                                        </td>
                                                                        <td>{{ $dise->dec }} </td>
                                                                        <td>{{ $dise->date }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        @if (App\models\disemp::all()->count() > 0)
                                                        @else
                                                            <p class="text-dark"> لايوجد بيانات </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="text-dark"> السلفه </label>
                                                <div class="card mg-b-20">
                                                    <div class="card-header pb-0">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">السلفه</th>
                                                                    <th scope="col">سبب السلفه</th>
                                                                    <th scope="col">التاريخ</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($salfs as $f)
                                                                    <tr>
                                                                        <td class="text-danger">{{ $f->salf }}</td>
                                                                        <td>{{ $f->dec }} </td>
                                                                        <td>{{ $f->date }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        @if (App\models\salf::all()->count() > 0)
                                                        @else
                                                            لا يوجد بيانات
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <label class="text-dark">تقرير الحوافز والمكافات </label>
                                            <div class="card mg-b-20">
                                                <div class="card-header pb-0">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">الحافز</th>
                                                                <th scope="col">سبب الحافز</th>
                                                                <th scope="col">التاريخ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($hafz as $h)
                                                                <tr>
                                                                    <td class="text-success">{{ $h->hafz }} جنيه
                                                                    </td>
                                                                    <td>{{ $h->dec }} </td>
                                                                    <td>{{ $h->date }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @if (App\models\hafz::all()->count() > 0)
                                                    @else
                                                        <p class="text-dark"> لايوجد بيانات </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="tab-pane" id="tab6">

                                        <div class="col-xl-12" style="margin-top: -10px;">
                                            {{-- <label class="text-dark" style="font-size:20px"> التقرير النهائي والمرتب </label> --}}
                                            <div class="card mg-b-20">
                                                <div class="card-header pb-0">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th> خصم دخول </th>
                                                                <th> خصم خروج </th>
                                                                <th> عدد ايام الغياب </th>
                                                                <th> خصم الغياب </th>
                                                                <th>خصم خارج البصمه</th>
                                                                <th>خصم سلف</th>
                                                                <th> عدد ساعات العمل</th>
                                                                <th> المرتب</th>
                                                                <th>المرتب بعد الخصم</th>
                                                                <th> عدد الاجازه </th>
                                                                <th> ot اجازه </th>
                                                                <th> عدد ساعات ot </th>
                                                                <th> ot </th>
                                                                <th> الحوافز </th>
                                                                <th> المرتب بعد ot </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <tr>
                                                                <td class="text-danger">{{ round($disin, 2) }}</td>
                                                                <td class="text-danger">{{ round($disout, 2) }}</td>
                                                                <td class="text-danger">{{ $absent }}</td>
                                                                <td class="text-danger">{{ number_format($disabsent, 2) }}
                                                                </td>
                                                                <td class="text-danger">{{ $disdis }}</td>
                                                                <td class="text-danger">{{ $dissalf }}</td>
                                                                {{-- <td class="text-success">{{ round($dayss) }}</td> --}}
                                                                <td class="text-success">
                                                                    {{ Carbon\Carbon::parse($dayss < 0 ? $dayss + 24 : $dayss)->format('H:i:s') }}
                                                                </td>
                                                                <td class="text-success">{{ $empkabd }}</td>
                                                                <td class="text-success">{{ number_format($afterdis, 2) }}
                                                                </td>
                                                                <td class="text-info">{{ $fri }}</td>
                                                                <td class="text-info">{{ round($friday) }}</td>
                                                                {{-- <td class="text-success">{{ round($othours) }}</td> --}}
                                                                <td class="text-success">
                                                                    {{ Carbon\Carbon::parse($othours)->format('H:i:s') }}
                                                                </td>
                                                                <td class="text-success">{{ number_format($overtime, 2) }}
                                                                </td>
                                                                <td class="text-success">{{ number_format($hafzs, 2) }}
                                                                </td>
                                                                <td class="text-success">{{ number_format($afterot, 2) }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    @if (App\models\count::count() > 0)
                                                    @else
                                                        لا يوجد بيانات
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



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


                </div>

            </div>


            <br>











        </div>
    </div>




    </div>
    </div>

@endsection
@section('js')
    <script>
        // function sumColumn() {
        // تحديد الجدول
        const table = document.getElementById('mytable');
        let sum = 0;
        let sums = 0;
        const rows = table.querySelectorAll('tbody tr'); // يحصل على جميع الصفوف داخل tbody
        rows.forEach(row => {
            // جلب قيمة الخلية في العمود الثاني (index 1)
            const value = parseFloat(row.cells[18].textContent.trim());
            if (!isNaN(value)) {
                sum += value;
            }
        });

        rows.forEach(row => {
            // جلب قيمة الخلية في العمود الثاني (index 1)
            const value = parseFloat(row.cells[17].textContent.trim());
            if (!isNaN(value)) {
                sums += value;
            }
        });

        // عرض المجموع
        document.getElementById('result').textContent = sum;
        document.getElementById('absent').textContent = sums;
        // }
    </script>




    {{-- <script> --}}
    {{-- // let sums = 0; --}}

    {{-- // rows.forEach(row => { --}}

    {{-- // const value = parseFloat(row.cells[1].textContent.trim()); --}}
    {{-- // if (!isNaN(value)) { --}}
    {{-- // sums += value; --}}
    {{-- // } --}}
    {{-- // }); --}}

    {{-- // document.getElementById('results').textContent = sums; --}}
    {{-- // </script> --}}


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
