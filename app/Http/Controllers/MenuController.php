<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{

    private $lastSubMenuId;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !Auth::user()->role->hasPermission('menus', 'list') ){
            return abort(403);
        }

        return view('admin.menus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->role->hasPermission('menus', 'create') ){
            return abort(403);
        }

        $menu = new Menu();
        return view('admin.menus.create', compact('menu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( !Auth::user()->role->hasPermission('menus', 'create') ){
            return abort(403);
        }

        $menu = new Menu();
        $menu = Menu::create( $this->validateRequest($menu) );

        $request->session()->flash('success', 'Menu Added Successfully!');
        return redirect()->route('admin.menus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        if( !Auth::user()->role->hasPermission('menus', 'view') ){
            return abort(403);
        }

        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        if( !Auth::user()->role->hasPermission('menus', 'edit') ){
            return abort(403);
        }

        return view('admin.menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        if( !Auth::user()->role->hasPermission('menus', 'edit') ){
            return abort(403);
        }

        $menu->update($this->validateRequest($menu));

        $request->session()->flash('success', 'Menu Updated Successfully!');
        return redirect()->route('admin.menus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        if( !Auth::user()->role->hasPermission('menus', 'delete') ){
            return abort(403);
        }

        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu Deleted Successfully!');
    }

    /**
     * Display a listing of the submenus.
     *
     * @return \Illuminate\Http\Response
     */
    public function submenus(Menu $menu)
    {
        if( !Auth::user()->role->hasPermission('menus', 'submenus') ){
            return abort(403);
        }

        $pages = Page::where('active', 1)->pluck('title', 'id')->all();

        $submenus = json_decode($menu->submenu_json, true);
        $html = '';

        if( $submenus ){
            $html = $this->recursilvelyGenerateSubmenuHtml($submenus, 0, $html = '');
        }

        $lastSubMenuId = $this->lastSubMenuId;

        return view('admin.menus.submenus', compact('menu', 'html', 'pages', 'lastSubMenuId'));
    }


    private function recursilvelyGenerateSubmenuHtml($array, $depth = 0, $html = '')
    {
        foreach ($array as $item)
        {
            $title = ''; $dataAttribute = ''; $type ='';
            if( isset($item['page']) ){
                $dataAttribute = 'data-page="'.$item['page'].'"';
                $type="page";
            } else if ( isset($item['anchor']) ){
                $dataAttribute = 'data-anchor="'.$item['anchor'].'"';
                $type="anchor";
            }

            if( isset($item['title']) ){
                $title = ' ('.$type.') '. $item['title'];

                if( isset($item['page']) ) {
                    $page = Page::where(['active' => 1, 'id' => $item['page']])->pluck('title', 'id')->first();
                    if (!$page) {
                        $title = ' (page not found) ' . $item['title'];
                    }
                }
            }

            $this->lastSubMenuId = $item['id'];

            $html .= '<li class="dd-item dd3-item" data-id="'.$item['id'].'" '.$dataAttribute.' data-title="'. $item['title'].'">
                    <div class="dd-handle dd3-handle"></div><div class="dd3-content">'. $title .'</div><div class="dd3-edit"><i class="fa fa-trash"></i></div>';

            if ( isset($item['children']) && count($item['children']) > 0) {
                $html .= '<ol class="dd-list">';
                $html .= $this->recursilvelyGenerateSubmenuHtml($item['children'], $depth + 1);
                $html .= '</ol>';
            }

            $html .= '</li>';
        }

        return $html;
    }

    public function submenusupdate(Menu $menu, Request $request)
    {
        $menuOrder = request()->input('menu_order');

        $menu->update(['submenu_json' => $menuOrder]);

        $request->session()->flash('success', 'Submenus Updated Successfully!');
        return redirect()->route('admin.menus.index');
    }

    public function list(Request $request)
    {
        if( !Auth::user()->role->hasPermission('menus', 'list') ){
            return abort(403);
        }

        if ($request->ajax()) {
            $data = Menu::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return (isset($row->name)) ? $row->name : '';
                })
                ->addColumn('theme', function ($row) {
                    return (isset($row->theme)) ? $row->theme : '';
                })
                ->addColumn('active', function ($row) {
                    return (isset($row->active)) ? $row->active : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( Auth::user()->role->hasPermission('menus', 'edit') ) {
                        $options .= '<a href="'. route('admin.menus.submenus',$row->id) .'" class="btn btn-secondary" title="Sub menus">
                            <i class="fas fa-bars"></i>
                        </a>';
                    }

                    if( Auth::user()->role->hasPermission('menus', 'edit') ) {
                        $options .= ' <a href="'. route('admin.menus.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if( Auth::user()->role->hasPermission('menus', 'delete') ) {
                        $options .= ' <form action="'. route('admin.menus.destroy', $row->id ) .'" method="POST" style="display: inline-block;">
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

    private function validateRequest($menu){

        return request()->validate([
            'name' => 'required|unique:menus,name,'.$menu->id,
            'theme' => 'required',
            'active' => 'required',
            'created_by' => '',
            'modified_by' => ''
        ]);
    }
}
