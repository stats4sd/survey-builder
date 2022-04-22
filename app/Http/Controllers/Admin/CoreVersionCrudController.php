<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CoreVersionRequest;
use App\Models\CoreVersion;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Storage;

/**
 * Class CoreVersionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CoreVersionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CoreVersion::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/core-version');
        CRUD::setEntityNameStrings('core version', 'core versions');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('version_name');
        CRUD::column('mini')->type('boolean');
        CRUD::column('file')->wrapper([
            'href' => function($crud, $column, $entry) {
                return Storage::url($entry->file);
            }
        ]);
        CRUD::column('published_at');
        CRUD::button('publish')->view('backpack::crud.buttons.publish')->stack('line');

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CoreVersionRequest::class);

        CRUD::field('prev_version')->type('prev_module_version_core');
        CRUD::field('version_name')->type('text');
        CRUD::field('mini')->type('checkbox');
        CRUD::field('file')->type('upload')->upload(true);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }


    protected function show($id)
    {
        $coreVersion = CoreVersion::find($id)->load('moduleVersions.module');

        return view('core_versions.show', ['coreVersion' => $coreVersion]);
    }


    public function getLatest ()
    {
        return CoreVersion::where('published_at', '!=', null)->orderBy('published_at', 'desc')->first();
    }

    public function publish (CoreVersion $coreversion)
    {
        $published = $coreversion->publish();

        \Alert::add('success', 'Module Version '.$coreversion->version_name.' has been successfully published at '.$published)->flash();

        return back();
    }


}
