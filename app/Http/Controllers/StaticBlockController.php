<?php

namespace App\Http\Controllers;

use App\Models\StaticBlock;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StaticBlockController extends Controller
{

    const MODULE = 'static_block';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!hasPermission(self::MODULE, 'list'), 401, __('messages.unauthorized_action'));

        return view('admin.static-block.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission(self::MODULE, "create"), 401, __('messages.unauthorized_action'));

        $staticBlock = new StaticBlock();
        return view('admin.static-block.create', compact('staticBlock'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!hasPermission(self::MODULE, "create"), 401, __('messages.unauthorized_action'));

        $staticBlock = new StaticBlock();
        $staticBlock = StaticBlock::create($this->validateRequest($staticBlock));

        $request->session()->flash('success', __('messages.record_created', ['module' => 'Static Block']));
        return redirect()->route('admin.static-block.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaticBlock  $staticBlock
     * @return \Illuminate\Http\Response
     */
    public function show(StaticBlock $staticBlock)
    {
        abort_if(!hasPermission(self::MODULE, "view"), 401, __('messages.unauthorized_action'));

        return view('admin.static-block.show', compact('staticBlock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StaticBlock  $staticBlock
     * @return \Illuminate\Http\Response
     */
    public function edit(StaticBlock $staticBlock)
    {
        abort_if(!hasPermission(self::MODULE, "edit"), 401, __('messages.unauthorized_action'));

        return view('admin.static-block.edit', compact('staticBlock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StaticBlock  $staticBlock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaticBlock $staticBlock)
    {
        abort_if(!hasPermission(self::MODULE, "edit"), 401, __('messages.unauthorized_action'));

        $staticBlock->update($this->validateRequest($staticBlock));

        $request->session()->flash('success', __('messages.record_updated', ['module' => 'Static Block']));
        return redirect()->route('admin.static-block.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaticBlock  $staticBlock
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaticBlock $staticBlock)
    {
        abort_if(!hasPermission(self::MODULE, "delete"), 401, __('messages.unauthorized_action'));

        $staticBlock->delete();
        return redirect()->route('admin.static-block.index')->with('success', __('messages.record_deleted', ['module' => 'Static Block']));
    }

    public function list (Request $request) 
    {
        abort_if(!hasPermission(self::MODULE, 'list'), 401, __('messages.unauthorized_action'));

        if ($request->ajax()) {
            $data = StaticBlock::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return ( isset($row->name)) ? $row->name : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( hasPermission(self::MODULE, 'edit') ) {
                        $options .= '<a href="'. route('admin.static-block.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if( hasPermission(self::MODULE, 'delete') ) {
                        $options .= ' <form action="'. route('admin.static-block.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($staticBlock) {
        return request()->validate([
            'name' => 'required|string|min:3|unique:static_blocks,name,' . $staticBlock->id,
            'identifier' => 'required|string',
            'contents' => 'required'
        ]);
    }
}
