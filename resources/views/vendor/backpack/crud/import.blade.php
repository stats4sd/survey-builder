@extends(backpack_view('blank'))

@php
  /** @noinspection PhpUndefinedVariableInspection */$defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.add') => false,
  ];
  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
        <h2>
        <span class="text-capitalize">{!! $crud->getSubheading() ?? 'Import '.$crud->entity_name_plural !!}.</span>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url()->previous() }}" class="hidden-print font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection

@section('content')

<div class="row">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

        <ul class="list-group">
            @if($errors->count() > 0)
                <li class="list-group-item list-group-item-primary">There were errors when importing the Excel file. No records have been imported. <br>
                Each error with the row number is listed below. Each one should be checked in the Excel file before attempting to re-upload.
                </li>
            @endif
            @foreach ($errors->all() as $message)
                <li class="list-group-item list-group-item-accent-danger">{{ $message }}</li>
            @endforeach
        </ul>

		  <form method="post"
		  		action="{{ url($crud->route.'/import') }}"
				enctype="multipart/form-data"
		  		>
			  {!! csrf_field() !!}
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->fields(), 'action' => 'import' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->fields(), 'action' => 'import' ])
		      @endif

	        <button type="submit" class="btn btn-success">
                <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                <span data-value="saveImport">Submit File</span>
            </button>

		  </form>
	</div>
</div>

@endsection
