<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //these pages must not be deleted and slugs can not be edited
    public $restrictedSlugs = ['home', 'privacy-policy', 'terms-and-conditions', 'thank-you'];
    
    //show the page
    public function show($id)
    {
        $page = Page::where('slug', $id)->firstOrFail();

        return view('pages', ['page' => $page]);
    }

    //show the list of pages
    public function index() {
        $pages = Page::paginate(config('app.pagination'));

        return view('admin.pages.index', [
            'page' => __('Pages'),
            'pages' => $pages,
        ]);
    }

    //return the create page
    public function create()
    {
        return view('admin.pages.create', [
            'page' => __('Pages'),
        ]);
    }

    //create language and store the file
    public function createPage(StorePageRequest $request)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $model = new Page();
        $model->title = $request->title;
        $model->slug = $request->slug;
        $model->footer = $request->footer ? 'yes' : 'no';
        $model->content = $request->content;
        $model->save();

        return redirect('/pages')->with('success', __('Settings saved.'));
    }

    //return the edit page
    public function edit($id)
    {
        $model = Page::find($id);

        return view('admin.pages.edit', [
            'page' => __('Pages'),
            'model' => $model,
            'restrictedSlugs' => $this->restrictedSlugs
        ]);
    }

    //update page
    public function updatePage(UpdatePageRequest $request, $id)
    {
        if (isDemoMode()) return back()->with('error', __('This feature is not available in demo mode'));

        $model = Page::find($id);

        $model->title = $request->title;
        $model->slug = $request->slug;
        $model->footer = $request->footer ? 'yes' : 'no';
        $model->content = $request->content;
        $model->save();

        return redirect('/pages')->with('success', __('Settings saved.'));
    }

    //delete language and file
    public function deletePage(Request $request)
    {
        if (isDemoMode()) return json_encode(['success' => false, 'error' => __('This feature is not available in demo mode')]);

        $model = Page::find($request->id);

        if (in_array($model->slug, $this->restrictedSlugs)) {
            return json_encode(['success' => false, 'error' => __('This page can not be deleted')]);
        }

        if ($model->delete()) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }
}
