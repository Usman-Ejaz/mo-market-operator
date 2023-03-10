<?php

namespace App\Http\Controllers;

use App\Models\ChatBotKnowledgeBase;
use Carbon\Carbon;
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
        abort_if(!hasPermission('knowledge_base', 'list'), 401, __('messages.unauthorized_action'));

        return view('admin.chatbot-knowledge-base.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission('knowledge_base', 'create'), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission('knowledge_base', 'create'), 401, __('messages.unauthorized_action'));

        $knowledgeBase = new ChatBotKnowledgeBase();
        $knowledgeBase = ChatBotKnowledgeBase::create($this->validateRequest($knowledgeBase));

        $message = __('messages.record_created', ['module' => 'Knowledge Base']);

        if ($request->action === "Published") {
            $knowledgeBase->published_at = now();
            $knowledgeBase->save();

            $message = __('messages.record_published', ['module' => 'Knowledge Base']);
        }

        $request->session()->flash('success', $message);
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
        abort_if(!hasPermission('knowledge_base', 'view'), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission('knowledge_base', 'edit'), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission('knowledge_base', 'edit'), 401, __('messages.unauthorized_action'));

        $message = __('messages.record_updated', ['module' => 'Knowledge Base']);

        if ($request->action === "Unpublished") {
            $knowledge_base->published_at = null;
            $knowledge_base->save();
            $message = __('messages.record_unpublished', ['module' => 'Knowledge Base']);
        } else if ($request->action === "Published") {
            $knowledge_base->published_at = now();
            $knowledge_base->save();
            $message = __('messages.record_published', ['module' => 'Knowledge Base']);
        }

        $knowledge_base->update($this->validateRequest($knowledge_base));

        $request->session()->flash('success', $message);
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
        abort_if(!hasPermission('knowledge_base', 'delete'), 401, __('messages.unauthorized_action'));

        $knowledge_base->delete();
        return redirect()->route('admin.knowledge-base.index')->with('success', __('messages.record_deleted', ['module' => 'Knowledge Base']));
    }

    /**
     * Get all faqs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Data to render in list/index view
     */
    public function list(Request $request)
    {
        abort_if(!hasPermission('knowledge_base', 'list'), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = ChatBotKnowledgeBase::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('question', function ($row) {
                    return truncateWords($row->question, 50);
                })
                ->addColumn('keywords', function ($row) {
                    return truncateWords($row->keywords, 20);
                })
                ->editColumn('created_at', function ($row) {
                    return [
                        'display' => $row->created_at,
                        'sort' => Carbon::parse(parseDate($row->created_at))->timestamp
                    ];
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if (hasPermission('knowledge_base', 'edit')) {
                        $options .= '<a href="' . route('admin.knowledge-base.edit', $row->id) . '" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }
                    if (hasPermission('knowledge_base', 'delete')) {
                        $options .= ' <button type="button" class="btn btn-danger deleteButton" data-action="'. route('admin.knowledge-base.destroy', $row->id ) .'" title="Delete">
                            <i class="fas fa-trash" data-action="'. route('admin.knowledge-base.destroy', $row->id ) .'"></i>
                        </button>';
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
            'keywords' => 'required|string',
            'created_by' => '',
            'modified_by' => ''
        ]);
    }
}
