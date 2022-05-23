<?php

namespace App\Http\Controllers;

use App\Models\FeedbackRating;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FeedbackRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! hasPermission('feedback_ratings', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.feedback-ratings.index');
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
     * @param  \App\Models\FeedbackRating  $feedbackRating
     * @return \Illuminate\Http\Response
     */
    public function show(FeedbackRating $feedbackRating)
    {
        abort_if(! hasPermission('feedback_ratings', 'view'), __('auth.error_code'), __('messages.unauthorized_action'));

        return view('admin.feedback-ratings.show', compact('feedbackRating'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeedbackRating  $feedbackRating
     * @return \Illuminate\Http\Response
     */
    public function edit(FeedbackRating $feedbackRating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeedbackRating  $feedbackRating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeedbackRating $feedbackRating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeedbackRating  $feedbackRating
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeedbackRating $feedbackRating)
    {
        //
    }

    public function list(Request $request)
    {
        abort_if(! hasPermission('feedback_ratings', 'list'), __('auth.error_code'), __('messages.unauthorized_action'));

        if ($request->ajax())
        {
            $feedbackRatings = FeedbackRating::latest()->get();

            return DataTables::of($feedbackRatings)
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
                    if (hasPermission('feedback_ratings', 'view')) {
                        $options .= ' <a href="'. route('admin.feedback-ratings.show',$row->id) .'" class="btn btn-primary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }

                    if (hasPermission('feedback_ratings', 'delete')) {
                        $options .= ' <form action="'. route('admin.feedback-ratings.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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
}
