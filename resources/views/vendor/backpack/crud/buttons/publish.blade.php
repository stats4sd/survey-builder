@if($entry->published_at === null)
<a href="{{ url($crud->route.'/'.$entry->id.'/publish') }}" class="btn btn-sm btn-link" data-button-type="publish"> Publish {{ $crud->entity_name }}</a>
@endif