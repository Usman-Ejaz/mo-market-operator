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
                ->addColumn('multiselect', function ($row) {
                    return '<input type="checkbox" id="checkbox_'. $row->id .'" class="multiselect" name="checkbox['.$row->id.']"/>';
                })
                ->addColumn('email', function ($row) {
                    return ($row->email) ? $row->email : '';
                })
                ->addColumn('newsletters', function ($row) {
                    return ($row->status) ? $row->status : '';
                })
                ->addColumn('rss_feed', function ($row) {
                    return ($row->rss_feed) ? $row->rss_feed : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('subscribers', 'subscribe_to_nl')) {
                        $class = $row->status == 'Subscribed' ? 'danger' : 'success';
                        $options .= '<form action="'. route('admin.subscribers.toggleSubscription', ['subscriber' => $row->id, 'type' => 'newsletters']) .'" method="POST" style="display: inline-block;">
                                '.csrf_field().'
                                <input type="hidden" name="status" value="' . ($row->status == 'Subscribed' ? 0 : 1) . '">
                                <button type="submit" class="btn btn-'.$class.' width-120"
                                    onclick="return confirm(\'Are You Sure Want to '.  ($row->status == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .'?\')" title="'. ($row->status == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .' to Newsletter">
                                        '. ($row->status == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .' to Newsletter
                                </button>
                            </form>';
                    }

                    if (hasPermission('subscribers', 'subscribe_to_rss')) {
                        $class = $row->rss_feed == 'Subscribed' ? 'danger' : 'primary';
                        $options .= '<form action="'. route('admin.subscribers.toggleSubscription', ['subscriber' => $row->id, 'type' => 'rss_feed']) .'" method="POST" style="display: inline-block;">
                                '.csrf_field().'
                                <input type="hidden" name="status" value="' . ($row->rss_feed == 'Subscribed' ? 0 : 1) . '">
                                <button type="submit" class="ml-2 btn btn-'.$class.' width-120"
                                    onclick="return confirm(\'Are You Sure Want to '.  ($row->rss_feed == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .'?\')" title="'. ($row->rss_feed == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .' to RSS Feed">
                                        '. ($row->rss_feed == 'Subscribed' ? 'Unsubscribe' : 'Subscribe') .' to RSS
                                </button>
                            </form>';
                    }
                    return $options;
                })
                ->rawColumns(['action', 'multiselect'])
                ->make(true);
        }
    }

    public function toggleSubscription(Request $request, Subscriber $subscriber, $type)
    {       
        $status = intval($request->get("status"));

        if ($type === "rss_feed") {
            abort_if(!hasPermission("subscribers", "subscribe_to_rss"), 401, __('messages.unauthorized_action'));
            $subscriber->update(['rss_feed' => $status]);
        } else {
            abort_if(!hasPermission("subscribers", "subscribe_to_nl"), 401, __('messages.unauthorized_action'));
            $subscriber->update(['status' => $status]);
        }

        $message = $status == 1 ? "subscribed" : "unsubscribed";

        return redirect()->route('admin.subscribers.index')->with('success', __('messages.subscriber', ['status' => $message]));
    }

    public function bulkToggle(Request $request)
    {
        abort_if(!(hasPermission("subscribers", "subscribe_to_nl") || hasPermission("subscribers", "subscribe_to_rss")), 401, __('messages.unauthorized_action'));

        if (!$request->ajax()) {
            return response(['message' => 'Baq Request'], 400);
        }
        $idsList = explode(',', $request->bulkIds);
        
        $status = $request->subscribe == "true" ? 1 : 0;
        $subscribers = Subscriber::find($idsList);

        foreach ($subscribers as $subscriber) {
            $subscriber->update(['status' => $status, 'rss_feed' => $status]);
        }

        $message = $status == 1 ? "subscribed" : "unsubscribed";

        $request->session()->flash('success', __('messages.subscribers', ['status' => $message]));
        return response(['success' => true], 200);
    }

    public function unsubscribe(Subscriber $subscriber, $type)
    {
        if ($type === "rss") {
            $subscriber->update(['rss_feed' => 0]);
        } else {
            $subscriber->update(['status' => 0]);
        }

        return back();
    }
}
