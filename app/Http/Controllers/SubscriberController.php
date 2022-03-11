<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
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
                ->addColumn('name', function ($row) {
                    return ($row->name) ? $row->name : '';
                })
                ->addColumn('email', function ($row) {
                    return ($row->email) ? $row->email : '';
                })
                ->addColumn('status', function ($row) {
                    return ($row->status) ? $row->status : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('subscribers', 'subscribe')) {
                        $options .= '<form action="'. route('admin.subscribers.toggleSubscription', $row->id) .'" method="POST" style="display: inline-block;">
                                '.csrf_field().'
                                <input type="hidden" name="status" value="' . ($row->status == 'Subscribed' ? 0 : 1) . '">
                                <button type="submit" class="btn btn-primary width-120"
                                    onclick="return confirm(\'Are You Sure Want to '.  ($row->status == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .'?\')" title="'. ($row->status == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .'">
                                        '. ($row->status == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .'
                                </button>
                            </form>';
                    }
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function toggleSubscription(Request $request, Subscriber $subscriber)
    {
        abort_if(!hasPermission("subscribers", "subscribe"), 401, __('messages.unauthorized_action'));
        
        $status = intval($request->get("status"));
        $subscriber->status = $status;
        $subscriber->save();

        $message = $status == 1 ? "Subscribed" : "Unsubscribed";

        return redirect()->route('admin.subscribers.index')->with('success', "Subscriber {$message} Successfully!");
    }
}
