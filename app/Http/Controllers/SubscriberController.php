<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("subscribers", "list"), 401, __('messages.unauthorized_action'));

        return view("admin.subscribers.index");
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("subscribers", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = Subscriber::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('multiselect', function ($row) {
                    return '<input type="checkbox" id="checkbox_'. $row->id .'" class="multiselect" name="checkbox['.$row->id.']"/>';
                })
                ->addColumn('email', function ($row) {
                    return ($row->email) ? $row->email : '';
                })
                ->addColumn('status', function ($row) {
                    return ($row->status) ? $row->status : '';
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('subscribers', 'subscribe')) {
                        $class = $row->status == 'Subscribed' ? 'danger' : 'success';
                        $options .= '<button type="button" class="btn btn-'.$class.'" onclick="handleSubscription(\''.  ($row->status == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .'\', '. $row->id .', \''. route('admin.subscribers.toggleSubscription', ['subscriber' => $row->id]) .'\')" title="'. ($row->status == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .' to Newsletter">
                                            '. ($row->status == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .'
                                    </button>';
                    }
                    return $options;
                })
                ->rawColumns(['action', 'multiselect'])
                ->make(true);
        }
    }

    public function toggleSubscription(Request $request, Subscriber $subscriber)
    {
        abort_if(!hasPermission("subscribers", "subscribe"), 401, __('messages.unauthorized_action'));

        $status = intval($request->get("status"));

        $subscriber->update(['status' => $status]);

        $message = $status == 1 ? "subscribed" : "unsubscribed";

        return response()->json(['success' => __('messages.subscriber', ['status' => $message])]);
    }

    public function bulkToggle(Request $request)
    {
        abort_if(!(hasPermission("subscribers", "subscribe")), 401, __('messages.unauthorized_action'));

        if (!$request->ajax()) {
            return response(['message' => 'Baq Request'], 400);
        }
        $idsList = explode(',', $request->bulkIds);

        $status = $request->subscribe == "true" ? 1 : 0;
        $subscribers = Subscriber::find($idsList);

        foreach ($subscribers as $subscriber) {
            $subscriber->update(['status' => $status]);
        }

        $message = $status == 1 ? "subscribed" : "unsubscribed";

        $request->session()->flash('success', __('messages.subscribers', ['status' => $message]));
        return response(['success' => true], 200);
    }

    public function unsubscribe(Subscriber $subscriber)
    {
        $subscriber->update(['status' => 0]);

        return back();
    }
}
