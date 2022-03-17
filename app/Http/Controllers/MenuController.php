<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Post;
use App\Models\Settings;
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
        abort_if(!hasPermission("menus", "list"), 401, __('messages.unauthorized_action'));

        $theme = Settings::where('name', 'current_theme')->first();

        return view('admin.menus.index', ['currentTheme' => $theme->value]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!hasPermission("menus", "create"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("menus", "create"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("menus", "view"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("menus", "edit"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("menus", "edit"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("menus", "delete"), 401, __('messages.unauthorized_action'));

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
        abort_if(!hasPermission("menus", "submenus"), 401, __('messages.unauthorized_action'));

        $pages = Page::where('published_at', '!=', null)->pluck('title', 'id')->all();
        $documentCategories = DocumentCategory::pluck('id', 'name')->all();
        $postCategories = (new Post)->postCategoryOptions();

        $submenus = json_decode($menu->submenu_json, true);
        $html = '';

        if( $submenus ){
            $html = $this->recursilvelyGenerateSubmenuHtml($submenus, 0, $html = '');
        }

        $lastSubMenuId = $this->lastSubMenuId;

        return view('admin.menus.submenus', compact('menu', 'html', 'pages', 'lastSubMenuId', 'documentCategories', 'postCategories'));
    }


    private function recursilvelyGenerateSubmenuHtml($array, $depth = 0, $html = '')
    {
        $counter = 1;
        foreach ($array as $item)
        {
            $title = ''; $dataAttribute = ''; $type ='';
            if( isset($item['page']) ){
                $dataAttribute = 'data-page="'.$item['page'].'"';
                $type="page";
            } else if ( isset($item['anchor']) ){
                $dataAttribute = 'data-anchor="'.$item['anchor'].'"';
                $type="anchor";
            } else if (isset($item['post'])) {
                $dataAttribute = 'data-post="'.$item['post'].'"';
                $type="post category";
            } else if (isset($item['doc'])) {
                $dataAttribute = 'data-doc="'.$item['doc'].'"';
                $type="document category";
            }

            if( isset($item['title']) ){
                $title = $counter . ' ('.$type.') '. $item['title'];

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
            $counter++;
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
        abort_if(!hasPermission("menus", "list"), 401, __('messages.unauthorized_action'));
        
        if ($request->ajax()) {
            $data = Menu::byTheme($request->query('theme'))->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return (isset($row->name)) ? $row->name : '';
                })
                ->addColumn('theme', function ($row) {
                    return (isset($row->theme)) ? config('settings.themes')[$row->theme] : '';
                })
                ->addColumn('active', function ($row) {
                    return (isset($row->active)) ? $row->active : '';
                })
                ->addColumn('created_at', function ($row) {
                    return ($row->created_at) ? $row->created_at : '';
                })
                ->addColumn('action', function ($row) {
                    $options = '';
                    if( hasPermission('menus', 'submenus') ) {
                        $options .= '<a href="'. route('admin.menus.submenus',$row->id) .'" class="btn btn-secondary" title="Sub menus">
                            <i class="fas fa-bars"></i>
                        </a>';
                    }

                    if( hasPermission('menus', 'edit') ) {
                        $options .= ' <a href="'. route('admin.menus.edit',$row->id) .'" class="btn btn-primary" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';
                    }

                    if( hasPermission('menus', 'delete') ) {
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

    public function search(Request $request) 
    {
        if (!$request->ajax()) {
            return response('Bad Request', 400);
        }

        $searchKey = $request->get('searchKey');

        switch($request->get('id')) {
            case 'page-search':
                return $this->searchPages($searchKey);
                break;
            case 'document-categories':
                return $this->searchDocumentCategories($searchKey);
                break;
            case 'post-categories':
                return $this->searchPostCategories($searchKey);
                break;
        }
    }

    private function searchPages($searchKey) 
    {
        $pages = Page::where('title', 'like', "%{$searchKey}%")->where('slug', 'like', "%{$searchKey}%");
        $pages = $pages->where('published_at', '!=', null)->get();
        
        $html = "";
        
        if ($pages->count() > 0) {
            foreach ($pages as $page) {
                $html .= '
                <li>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="pages['. $page->id .']" value="" data-page="'. $page->id .'" data-title="'. $page->title .'">
                            '. truncateWords($page->title, 35) .'
                            <a href="'. route("admin.pages.edit", $page->id) .'" target="_blank"> <i class="fa fa-link"></i></a>
                        </label>
                    </div>
                </li>
                ';
            }
        }
        return $html;
    }

    private function searchDocumentCategories($searchKey) 
    {
        $documentCategories = DocumentCategory::where('name', 'like', "%{$searchKey}%")->get();
        
        $html = "";
        
        if ($documentCategories->count() > 0) {
            foreach ($documentCategories as $documentCategory) {
                $html .= '
                <li>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="documentCategories['. $documentCategory->id .']" value="" data-doc="'. $documentCategory->id .'" data-title="'. $documentCategory->name .'">
                            '. truncateWords($documentCategory->name, 35) .'
                            <a href="'. route("admin.document-categories.edit", $documentCategory->id) .'" target="_blank"> <i class="fa fa-link"></i></a>
                        </label>
                    </div>
                </li>
                ';
            }
        }
        return $html;
    }

    private function searchPostCategories($searchKey) 
    {
        $post = new Post;
        $postCategories = collect($post->postCategoryOptions());

        if (!empty($searchKey)) {
            $postCategories = $postCategories->filter( function ($item) use ($searchKey) { 
                return strpos(strtolower($item), strtolower($searchKey)) !== false;
            });
        }

        $html = "";
        if ($postCategories->count() > 0) {
            foreach ($postCategories as $key => $documentCategory) {
                $html .= '
                <li>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="postCategories['. $key .']" value="" data-post="'. $key .'" data-title="'. $documentCategory .'">
                            '. truncateWords($documentCategory, 35) .'
                        </label>
                    </div>
                </li>
                ';
            }
        }
        return $html;
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
