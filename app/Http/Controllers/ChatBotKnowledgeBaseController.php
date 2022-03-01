<?php

namespace App\Http\Controllers;

use App\Models\ChatBotKnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ChatBotKnowledgeBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->role->hasPermission('knowledge-base', 'list')) {
            return abort(403);
        }

        return view('admin.chatbot-knowledge-base.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->role->hasPermission('knowledge-base', 'create') ){
            return abort(403);
        }

        $knowledge_base = new ChatBotKnowledgeBase();
        return view('admin.chatbot-knowledge-base.create', compact('knowledge_base'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->role->hasPermission('knowledge-base', 'create')) {
            return abort(403);
        }

        $knowledgeBase = new ChatBotKnowledgeBase();
        $knowledgeBase = ChatBotKnowledgeBase::create($this->validateRequest($knowledgeBase));

        if ($request->action === "Published") {
            $knowledgeBase->published_at = now();
            $knowledgeBase->save();
        }
        
        $request->session()->flash('success', "Knowledge Base {$request->action} Successfully!");
        return redirect()->route('admin.knowledge-base.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChatBotKnowledgeBase  $chatBotKnowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function show(ChatBotKnowledgeBase $chatBotKnowledgeBase)
    {
        if (!Auth::user()->role->hasPermission('knowledge-base', 'view')) {
            return abort(403);
        }

        return view('admin.chatbot-knowledge-base.show', compact('chatBotKnowledgeBase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChatBotKnowledgeBase  $chatBotKnowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function edit(ChatBotKnowledgeBase $knowledge_base)
    {
        if (!Auth::user()->role->hasPermission('knowledge-base', 'edit')) {
            return abort(403);
        }

        return view('admin.chatbot-knowledge-base.edit', compact('knowledge_base'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChatBotKnowledgeBase  $chatBotKnowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChatBotKnowledgeBase $knowledge_base)
    {   
        if (!Auth::user()->role->hasPermission('knowledge-base', 'edit')) {
            return abort(403);
        }

        if ($request->action === "Unpublished") {
            $knowledge_base->published_at = null;
            $knowledge_base->save();
        } else if ($request->action === "Published") {
            $knowledge_base->published_at = now();
            $knowledge_base->save();
        }

        $knowledge_base->update($this->validateRequest($knowledge_base));

        $request->session()->flash('success', "Knowledge Base {$request->action} Successfully!");
        return redirect()->route('admin.knowledge-base.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChatBotKnowledgeBase $chatBotKnowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChatBotKnowledgeBase $knowledge_base)
    {
        if (!Auth::user()->role->hasPermission('knowledge-base', 'delete')) {
            return abort(403);
        }

        $knowledge_base->delete();
        return redirect()->route('admin.knowledge-base.index')->with('success', 'Knowledge Base Deleted Successfully!');
    }

    /**
     * Get all faqs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Data to render in list/index view
     */
    public function list(Request $request)
    {
        if (!Auth::user()->role->hasPermission('knowledge-base', 'list')) {
            return abort(403);
        }

        if ($request->ajax()) {
            $data = ChatBotKnowledgeBase::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('question', function ($row) {
                    return truncateWords($row->question, 30);
                })
                ->addColumn('answer', function ($row) {
                    return truncateWords($row->answer, 50);
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (Auth::user()->role->hasPermission('knowledge-base', 'edit')) {
                        $options .= '<a href="' . route('admin.knowledge-base.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if (Auth::user()->role->hasPermission('knowledge-base', 'delete')) {
                        $options .= ' <form action="'. route('admin.knowledge-base.destroy', $row->id) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest ($knowledgeBase) {
        return request()->validate([
            'question' => 'required|min:5|unique:chat_bot_knowledge_bases,question,'.$knowledgeBase->id,
            'answer' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ]);
    }
}
