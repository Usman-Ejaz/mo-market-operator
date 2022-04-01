<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewsletterEmail;
use App\Mail\NewsletterEmail;
use App\Models\Newsletter;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission("newsletters", "list"), 401, __('messages.unauthorized_action'));

        return view('admin.newsletters.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("newsletters", "create"), 401, __('messages.unauthorized_action'));

        $newsletter = new Newsletter();
        return view('admin.newsletters.create', compact('newsletter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!hasPermission("newsletters", "create"), 401, __('messages.unauthorized_action'));

        $newsletter = new Newsletter();
        $newsletter = Newsletter::create( $this->validateRequest($newsletter) );

        $request->session()->flash('success', 'Newsletter Added Successfully!');
        return redirect()->route('admin.newsletters.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function show(Newsletter $newsletter)
    {
        abort_if(!hasPermission("newsletters", "view"), 401, __('messages.unauthorized_action'));

        return view('admin.newsletters.show', compact('newsletter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function edit(Newsletter $newsletter)
    {
        abort_if(!hasPermission("newsletters", "edit"), 401, __('messages.unauthorized_action'));

        return view('admin.newsletters.edit', compact('newsletter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        abort_if(!hasPermission("newsletters", "edit"), 401, __('messages.unauthorized_action'));

        $newsletter->update($this->validateRequest($newsletter));

        $request->session()->flash('success', 'Newsletter Updated Successfully!');
        return redirect()->route('admin.newsletters.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Newsletter $newsletter)
    {
        abort_if(!hasPermission("newsletters", "delete"), 401, __('messages.unauthorized_action'));

        $newsletter->delete();
        return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter Deleted Successfully!');
    }

    public function list(Request $request)
    {
        abort_if(!hasPermission("newsletters", "list"), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = Newsletter::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('subject', function ($row) {
                    return truncateWords($row->subject, 80);
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( hasPermission('newsletters', 'sendNewsLetter') ) {
                        $options .= '<form action="'. route('admin.newsletters.sendNewsLetter', $row->id ) .'" method="POST" style="display: inline-block;">
                                '.csrf_field().'
                                <button type="submit" class="btn btn-info"
                                    onclick="return confirm(\'Are You Sure Want to send this newsletter to subscribers?\')" title="Send Newsletter">
                                        <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>';
                    }

                    if( hasPermission('newsletters', 'edit') ) {
                        $options .= ' <a href="'. route('admin.newsletters.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if( hasPermission('newsletters', 'delete') ) {
                        $options .= ' <form action="'. route('admin.newsletters.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are You Sure Want to delete this record?\')" title="Delete">
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

    private function validateRequest($newsletter){

        return tap( request()->validate([
            'subject' => 'required|min:3',
            'description' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ]), function(){
        });
    }

    public function sendNewsLetter(Request $request, Newsletter $newsletter) 
    {
        abort_if(!hasPermission("newsletters", "sendNewsLetter"), 401, __('messages.unauthorized_action'));

        dispatch(new SendNewsletterEmail($newsletter));

        $request->session()->flash('success', 'Newsletter Sent Successfully!');
        return redirect()->route('admin.newsletters.index');
    }
}

