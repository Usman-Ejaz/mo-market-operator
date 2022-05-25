<?php

namespace App\Http\Controllers;

use App\Events\QueryReplyEvent;
use App\Mail\ContactQueryReplyMail;
use App\Models\ChatbotInitiator;
use App\Models\ContactPageQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ContactPageQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("contact_page_queries", "list"), 401, __('messages.unauthorized_action'));

        return view("admin.contact-page-queries.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactPageQuery  $contactPageQuery
     * @return \Illuminate\Http\Response
     */
    public function show(ContactPageQuery $contactPageQuery)
    {
        abort_if(!hasPermission("contact_page_queries", "view"), 401, __('messages.unauthorized_action'));

        if (request()->query('notification')) {
            $notification = auth()->user()->notifications()->where('id', '=', request()->query('notification'))->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }
        
        $chatbotQuery = ChatbotInitiator::where('email', '=', $contactPageQuery->email)->first();
        $isChatbotQuery = false;

        if ($chatbotQuery) {
            $contactPageQuery->phone = $chatbotQuery->phone;
            $contactPageQuery->company = $chatbotQuery->company;
            $contactPageQuery->update();

            $isChatbotQuery = true;
        }

        return view("admin.contact-page-queries.show", compact('contactPageQuery', 'isChatbotQuery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactPageQuery  $contactPageQuery
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactPageQuery $contactPageQuery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactPageQuery  $contactPageQuery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactPageQuery $contactPageQuery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactPageQuery  $contactPageQuery
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactPageQuery $contactPageQuery)
    {
        abort_if(!hasPermission("contact_page_queries", "delete"), 401, __('messages.unauthorized_action'));

        $contactPageQuery->delete();
        return redirect()->route('admin.contact-page-queries.index')->with('success', __('messages.record_deleted', ['module' => 'Contact Query']));
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("contact_page_queries", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = ContactPageQuery::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return truncateWords($row->name, 20);
                })  
                ->addColumn('email', function ($row) {
                    return ($row->email) ? $row->email : '';
                })
                ->addColumn('subject', function ($row) {
                    return truncateWords($row->subject, 25);
                })
                ->addColumn('message', function ($row) {
                    return truncateWords($row->message, 25);
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                   $options = '';
                    if (hasPermission('contact_page_queries', 'edit')) {
                        $options .= '<a href="' . route('admin.contact-page-queries.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if (hasPermission('contact_page_queries', 'view')) {
                        $options .= '<a href="' . route('admin.contact-page-queries.show', $row->id) . '" class="btn btn-primary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }
                    if (hasPermission('contact_page_queries', 'delete')) {
                        $options .= ' <form action="'. route('admin.contact-page-queries.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\''. __('messages.record_delete') .'\')" title="Delete">
                                    <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                    }
                    return $options;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function addReply(Request $request, ContactPageQuery $contactPageQuery)
    {
        $request->validate([
            'reply' => 'required|string|min:2|max:200'
        ]);

        $contactPageQuery->update([
            'comments' => $request->reply,
            'status' => 'resolved',
            'resolved_by' => auth()->id()
        ]);

        event(new QueryReplyEvent($contactPageQuery));        

        $request->session()->flash('success', __('messages.reply_email'));

        return redirect()->route('admin.contact-page-queries.index');
    }
}
