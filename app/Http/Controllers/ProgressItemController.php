<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultResource;
use App\Models\Instructor;
use App\Models\ProgressItem;
use App\Models\ProgressItemUserRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressItemController extends Controller
{
    public function getProgressItemsForStudent(int $type)
    {
        request()->validate(['student_id' => 'required|exists:students,id']);
        request()->validate(['student_id' => 'required|exists:students,id']);
        $student_id = request('student_id');
        $result= ProgressItem::whereType($type)
            ->with(['progressItemUserRate' => function ($query) use ($student_id) {
                $query->where('student_id', $student_id);
            }])
            ->get()
            ->map(function ($row) {
                $row['rate'] = $row['progressItemUserRate'][0]->rate??0;
                unset($row['progressItemUserRate']);

                return $row;
            });

            return DefaultResource::make($result);
    }

    public function getProgressItemsGroupForStudent()
    {
        request()->validate(['student_id' => 'required|exists:students,id']);
        $student_id = request('student_id');

        $result = collect();
        for ($i = 0; $i < 4; $i++) {
            $result->add([
                'type' => $i,
                'total_count' => 4,
                "total_sum" => 700,
                'sum' => 200
            ]);
        }

        // return $result;

        $count =  ProgressItem::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->keyBy('type');


        $result = $result->map(function ($row) use ($count) {

            $row['total_count'] = $count[$row['type']]->total ?? 0;
            $row['total_sum'] = $row['total_count'] * 100;

            return $row;
        });

        // return $result;


        $rates =  ProgressItemUserRate::where('instructor_id', $student_id)
            ->with(['progressItem'])
            ->where('student_id', $student_id)
            ->get()
            ->map(function ($row) {
                // return $row;
                return [

                    'rate' => $row->rate,
                    'type' => $row->progressItem->type
                ];
            })
            ->groupBy('type');



        // return collect($rates[0]??[])->sum(fn ($row) => $row['rate'])??0;



        $result = $result->map(function ($row) use ($rates) {

            $sum = collect($rates[$row['type']] ?? [])->sum(fn ($row) => $row['rate']) ?? 0;;


            $row['sum'] = $sum;


            return $row;
        });


        return DefaultResource::make($result);
    }

    public function rateStudent()
    {

        $rules = [
            'student_id' => 'required|exists:students,id',
            'data' => ['required', 'array'],
            'data.*.rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'data.*.progress_item_id' => ['required', 'exists:progress_items,id']
        ];
        request()->validate($rules);


        $student_id = request('student_id');
        $data = request('data');
        /** @var Instructor */
        $instructor = auth()->user()->instructor;

        $data_to_be_inserted = collect();

        foreach ($data as $rate_item) {

            $unique = [
                'student_id' => $student_id,
                'instructor_id' => $instructor->id,
                'progress_item_id' => $rate_item['progress_item_id']
            ];
            $row = [
                'instructor_id' => $instructor->id,
                'student_id' => $student_id,
                'progress_item_id' => $rate_item['progress_item_id'],
                'rate' => $rate_item['rate']
            ];
            $data_to_be_inserted->add($row);
            ProgressItemUserRate::updateOrCreate($unique, ['rate' => $rate_item['rate']]);
        }


        return DefaultResource::make($data_to_be_inserted);
    }
}
