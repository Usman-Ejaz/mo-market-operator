<?php

namespace App\Http\Controllers;

use App\Models\ChatbotFeedback;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ChatbotFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! hasPermission('chatbot_feedback', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.chatbot-feedbacks.index');
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
     * @param  \App\Models\ChatbotFeedback  $chatbotFeedback
     * @return \Illuminate\Http\Response
     */
    public function show(ChatbotFeedback $chatbotFeedback)
    {
        abort_if(! hasPermission('chatbot_feedback', 'view'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.chatbot-feedbacks.show', compact('feedbackRating'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChatbotFeedback  $chatbotFeedback
     * @return \Illuminate\Http\Response
     */
    public function edit(ChatbotFeedback $chatbotFeedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChatbotFeedback  $chatbotFeedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChatbotFeedback $chatbotFeedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChatbotFeedback  $chatbotFeedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChatbotFeedback $chatbotFeedback)
    {
        abort_if(! hasPermission('chatbot_feedback', 'delete'), __('auth.error_code'), __('messages.unauthorized_action'));

        $chatbotFeedback->delete();

        return redirect()->route('admin.chatbot-feedbacks.index')->with('success', __('messages.record_deleted', ['module' => 'Chatbot Feedback']));
    }

    public function list(Request $request)
    {
        abort_if(! hasPermission('chatbot_feedback', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        if ($request->ajax())
        {
            $chatbotFeedbacks = ChatbotFeedback::latest()->get();

            return DataTables::of($chatbotFeedbacks)
                ->addIndexColumn()
                ->addColumn('email', function ($row) {
                    return $row->owner ? $row->owner->email : '';
                })
                ->addColumn('rating', function ($row) {
                    return (isset($row->rating)) ? $row->rating : '';
                })
                ->addColumn('feedback', function ($row) {
                    return (isset($row->feedback)) ? truncateWords($row->feedback, 20) : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('chatbot_feedback', 'view')) {
                        $options .= ' <a href="'. route('admin.chatbot-feedbacks.show',$row->id) .'" class="btn btn-primary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }

                    if (hasPermission('chatbot_feedback', 'delete')) {
                        $options .= ' <form action="'. route('admin.chatbot-feedbacks.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm(\'Are you sure you want to delete this record?\')" title="Delete">
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
}
