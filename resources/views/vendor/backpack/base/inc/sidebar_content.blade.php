<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>


<h4 class="nav-item pl-3 py-4">Module Properties</h4>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('theme') }}'><i class='nav-icon las la-info'></i> Themes</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('author') }}'><i class='nav-icon la la-user'></i> Authors</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('indicator') }}'><i class="nav-icon las la-digital-tachograph"></i> Indicators</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('sdg') }}'><i class='nav-icon las la-globe-africa'></i> SDG Indicators</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('language') }}'><i class='nav-icon las la-language'></i> Languages</a></li>
<hr/>

<h4 class="nav-item pl-3 py-4">Modules</h4>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('module') }}'><i class='nav-icon la la-puzzle-piece'></i> All Modules</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('choice-list') }}'><i class='nav-icon la la-question'></i> Choice lists</a></li>

<h4 class="nav-item pl-3 py-4">User Section</h4>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('project') }}'><i class='nav-icon la la-users'></i> Projects</a></li>

<h4 class="nav-item pl-3 py-4">Platform Admin</h4>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('user') }}'><i class='nav-icon la la-user'></i> Users</a></li>
