<?php

namespace App\Http\Controllers;

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
        if( !Auth::user()->role->hasPermission('newsletters', 'list') ){
            return abort(403);
        }

        return view('admin.newsletters.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->role->hasPermission('newsletters', 'create') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('newsletters', 'create') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('newsletters', 'view') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('newsletters', 'edit') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('newsletters', 'edit') ){
            return abort(403);
        }

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
        if( !Auth::user()->role->hasPermission('newsletters', 'delete') ){
            return abort(403);
        }

        $newsletter->delete();
        return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter Deleted Successfully!');
    }

    public function list(Request $request)
    {
        if( !Auth::user()->role->hasPermission('newsletters', 'list') ){
            return abort(403);
        }

        if ($request->ajax()) {
            $data = Newsletter::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('subject', function ($row) {
                    return ($row->subject) ? ( (strlen($row->subject) > 80) ? substr($row->subject,0,80).'...' : $row->subject ) : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( Auth::user()->role->hasPermission('newsletters', 'sendNewsLetter') ) {
                        $options .= '<form action="'. route('admin.newsletters.sendNewsLetter', $row->id ) .'" method="POST" style="display: inline-block;">
                                '.csrf_field().'
                                <button type="submit" class="btn btn-info"
                                    onclick="return confirm(\'Are You Sure Want to send this newsletter to subscribers?\')" title="Send Newsletter">
                                        <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>';
                    }

                    if( Auth::user()->role->hasPermission('newsletters', 'edit') ) {
                        $options .= ' <a href="'. route('admin.newsletters.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if( Auth::user()->role->hasPermission('newsletters', 'delete') ) {
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
            'description' => 'required|min:10',
            'created_by' => '',
            'modified_by' => ''
        ]), function(){
        });
    }

    public function sendNewsLetter(Request $request, Newsletter $newsletter) {
        
        if (!Auth::user()->role->hasPermission('newsletters', 'sendNewsLetter')) {
            return abort(403);
        }
        
        $subscribers = Subscriber::active()->select("email")->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterEmail($newsletter));
        }

        $request->session()->flash('success', 'Newsletter Sent Successfully!');
        return redirect()->route('admin.newsletters.index');
    }
}

