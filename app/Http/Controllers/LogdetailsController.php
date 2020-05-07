<?php

namespace App\Http\Controllers;

use App\tbl_user_log_details;
use DB;
use Illuminate\Http\Request;

class LogdetailsController extends Controller
{
    public function logdetails(Request $request)
    {
        $this->validate(
            $request,
            [
                'draw'           => 'required|integer|between:0,9999999999',
                'start'          => 'required|integer|between:0,999999999',
                'length'         => 'required|integer|between:0,100',
                'order'          => 'array',
                'search.*'       => 'nullable|regex:/^[A-Za-z0-9\s]+$/i',
                'order.*.column' => 'required|integer|between:0,6',
                'order.*.dir'    => 'required|in:asc,desc',
            ],
            [
                'draw.required' => 'Invalid Input',
                'draw.between'  => 'Invalid Input',
                'draw.integer'  => 'Invalid Input',

                'start.required' => 'Invalid Input',
                'start.between'  => 'Invalid Input',
                'start.integer'  => 'Invalid Input',

                'length.required' => 'Invalid Input',
                'length.between'  => 'Invalid Input',
                'length.integer'  => 'Invalid Input',

                'order.*.column.required' => 'Invalid Input',
                'order.*.column.integer'  => 'Invalid Input',
                'order.*.column.between'  => 'Invalid Input',

                'order.array' => 'Invalid Input',

                'order.*.dir.required' => 'Invalid Input',
                'order.*.dir.in'       => 'Invalid Input',

                'search.*.regex' => 'Invalid Input',
            ]
        );

        $draw = $request->draw;
        $offset = $request->start;
        $length = $request->length;
        $search = isset($request->search['value']) ? $request->search['value'] : '';
        $order = $request->order;

        $data = [];
        $record = tbl_user_log_details::leftjoin('tbl_user', 'tbl_user.code', 'tbl_user_log_details.userCode')
        ->select(
            'tbl_user_log_details.code',
            'tbl_user.name',
            'userCode',
            'userIp',
            'visitedPage',
            'tbl_user_log_details.created_at',
            'tbl_user_log_details.browser'
        )
                ->orderby('tbl_user_log_details.code', 'desc')
        ->where(
            function ($q) use ($search) {
                $q->orwhere('tbl_user.name', 'like', '%'.$search.'%');
                $q->orwhere('userIp', 'like', '%'.$search.'%');
                $q->orwhere('visitedPage', 'like', '%'.$search.'%');
                $q->orwhere('browser', 'like', '%'.$search.'%');
            }
        );

        $filtered_count = $record->count();
        $page_displayed = $record->offset($offset)->limit($length)->get();
        $count = $offset + 1;

        foreach ($page_displayed as $row) {
            $nestedData['code'] = $count;
            if ($row->userCode == null) {
                $nestedData['userCode'] = 'Anonymous USer';
            } else {
                $nestedData['userCode'] = $row->name;
            }
            $nestedData['userIp'] = $row->userIp;
            $nestedData['visitedPage'] = $row->visitedPage;
            $nestedData['browser'] = $row->browser;
            $nestedData['created_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d/m/Y H:i:s');
            $view_button = $row->code;
            $nestedData['action'] = ['v' => $view_button];
            $count++;
            $data[] = $nestedData;
        }
        $response = [
            'draw'            => $draw,
            'recordsTotal'    => $filtered_count,
            'recordsFiltered' => $filtered_count,
            'record_details'  => $data,
        ];

        return response()->json($response);
    }

    public function logview(Request $request)
    {
        //  dd($request->all());
        $view = tbl_user_log_details::leftjoin('tbl_user', 'tbl_user.code', 'tbl_user_log_details.userCode')
        ->select(
            'tbl_user_log_details.*',
            'tbl_user.name',
            'tbl_user.mobile_no',
            'tbl_user.designation',
            'tbl_user.user_type',
            DB::raw('DATE_FORMAT(tbl_user_log_details.created_at, "%d/%m/%Y") as created'),
            DB::raw(
                'DATE_FORMAT(tbl_user_log_details.created_at, "%d/%m/%Y") as updated',
                'tbl_user_log_details.browser'
            )
        )
        ->where('tbl_user_log_details.code', $request->code)
        ->first();

        $details = ['options'=>$view];

        return response()->json($details);
    }

    public static function get_visitor_count()
    {
        $v_count = tbl_user_log_details::where('visitor_count', 1)->count();

        return $v_count;
    }
}
