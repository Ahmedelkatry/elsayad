<?php

namespace App\Imports;

use App\Models\employees;
use Carbon\Carbon;
use App\Models\salf;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class salfs implements
    ToCollection
{
    private $i = 0;
    public function collection(Collection $rows)
    {

        // $this->i++;
        // if ($this->i > 0) {
            foreach ($rows->skip(1)  as $row) {

                if (empty($row[0])) {
                    break; // إنهاء التكرار
                }

                $date = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($row[3] - 2)->toDateString();
                $id_emp=employees::where("num",$row[0])->first()->id;
                $id_sec=employees::find($id_emp)->section->id;

                // dd($date);
                    salf::create([
                        'employees_id' => $id_emp,
                        'salf' => $row[2],
                        'date' => $date,
                        'section_id' => $id_sec,
                    ]);
                // }
            // }
        }
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // public function model(array $row)
    // {
    // return new salf([

    // ]);
    // }
}
