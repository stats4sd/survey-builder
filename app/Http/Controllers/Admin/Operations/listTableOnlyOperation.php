<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait listTableOnlyOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setuplistTableOnlyRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/listtableonly', [
            'as'        => $routeName.'.listtableonly',
            'uses'      => $controller.'@listtableonly',
            'operation' => 'listtableonly',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setuplistTableOnlyDefaults()
    {
        $this->crud->allowAccess('listtableonly');

        $this->crud->operation('listtableonly', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig('list');
        });

    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function listtableonly()
    {
        $this->crud->hasAccessOrFail('listtableonly');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? 'listtableonly '.$this->crud->entity_name;

        // load the view
        return view("crud::operations.listtableonly", $this->data);
    }
}
