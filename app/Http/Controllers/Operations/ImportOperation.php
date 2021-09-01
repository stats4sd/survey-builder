<?php

namespace App\Http\Controllers\Operations;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;

trait ImportOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupImportRoutes($segment, $routeName, $controller)
    {
        Route::get($segment . '/import', [
            'as'        => $routeName . '.getImport',
            'uses'      => $controller . '@getImportForm',
            'operation' => 'import',
        ]);

        Route::post($segment . '/import', [
            'as'         => $routeName.'.postImport',
            'uses'       => $controller.'@postImportForm',
            'operation'  => 'import',

        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupImportDefaults()
    {
        $this->crud->allowAccess('import');

        $this->crud->operation('import', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
            $this->crud->setupDefaultSaveActions();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('top', 'import', 'view', 'crud::buttons.import');
        });
    }

    public function getImportForm()
    {
        $this->crud->hasAccessOrFail('import');
        $this->crud->setOperation('import');

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();

        $this->data['title'] = 'Import '.$this->crud->entity_name.' from excel file';

        $this->crud->addField([
            'name' => 'import_file',
            'type' => 'upload',
            'label' => 'Select Excel File to Upload',

        ]);

        return view('crud::import', $this->data);
    }


    /**
     * Import All rows to Excel
     *
     * @return \Illuminate\View\View
     */
    public function postImportForm()
    {
        $this->crud->hasAccessOrFail('import');
        $importer = $this->crud->get('import.importer');

        if (!$importer) {
            return response("Importer Class not found - please check the importer is properly setup for this page", 500);
        }

        $request = $this->crud->validateRequest();


        Excel::import(new $importer, $request->import_file);




        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        if ($route = $this->crud->get('import.redirect')) {
            return redirect(url($route));
        }
        return redirect(url($this->crud->route));
    }
}