<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Author
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Author[] $modules
 * @property-read int|null $modules_count
 * @method static \Illuminate\Database\Eloquent\Builder|Author newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Author newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Author query()
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereUpdatedAt($value)
 */
	class Author extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CoreVersion
 *
 * @property int $id
 * @property string $version_name
 * @property int $mini is this a Reduced / shortened version of a module?
 * @property string $file
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ModuleVersion[] $moduleVersions
 * @property-read int|null $module_versions_count
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion whereMini($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoreVersion whereVersionName($value)
 */
	class CoreVersion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Indicator
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Module[] $modules
 * @property-read int|null $modules_count
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereUpdatedAt($value)
 */
	class Indicator extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Language
 *
 * @property string $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsforms\ChoicesLabel[] $choicesLabels
 * @property-read int|null $choices_labels_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Module[] $modules
 * @property-read int|null $modules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsforms\SurveyLabel[] $surveyLabels
 * @property-read int|null $survey_labels_count
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 */
	class Language extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Modifier
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int|null $theme_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ModuleVersion[] $moduleVersions
 * @property-read int|null $module_versions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Module[] $modules
 * @property-read int|null $modules_count
 * @property-read \App\Models\Theme|null $theme
 * @method static \Illuminate\Database\Eloquent\Builder|Modifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Modifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Modifier query()
 * @method static \Illuminate\Database\Eloquent\Builder|Modifier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modifier whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modifier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modifier whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modifier whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modifier whereUpdatedAt($value)
 */
	class Modifier extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Module
 *
 * @property int $id
 * @property string $slug
 * @property int $theme_id
 * @property string $title
 * @property string|null $logo
 * @property string|null $localisation_needs
 * @property string|null $r_scripts
 * @property mixed|null $requires list of other modules that this module requires / relies on.
 * @property mixed|null $requires_before list of other modules that must come before this module in the survey.
 * @property int $minutes
 * @property int $core is the module part of the RHOMIS core?
 * @property int $live is the module available for use?
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $parent_id
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Author[] $authors
 * @property-read int|null $authors_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsforms\ChoicesRow[] $choicesRows
 * @property-read int|null $choices_rows_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ModuleVersion[] $draftVersions
 * @property-read int|null $draft_versions_count
 * @property-read mixed $current_file
 * @property-read mixed $current_version
 * @property-read mixed $current_version_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Indicator[] $indicators
 * @property-read int|null $indicators_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language[] $languages
 * @property-read int|null $languages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Modifier[] $modifiers
 * @property-read int|null $modifiers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ModuleVersion[] $moduleVersions
 * @property-read int|null $module_versions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ModuleVersion[] $publishedVersions
 * @property-read int|null $published_versions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sdg[] $sdgs
 * @property-read int|null $sdgs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsforms\SurveyRow[] $surveyRows
 * @property-read int|null $survey_rows_count
 * @property-read \App\Models\Theme $theme
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsform[] $xlsforms
 * @property-read int|null $xlsforms_count
 * @method static \Illuminate\Database\Eloquent\Builder|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module query()
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereCore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereLocalisationNeeds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereRScripts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereRequires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereRequiresBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereThemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereUpdatedAt($value)
 */
	class Module extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ModuleVersion
 *
 * @property int $id
 * @property int $module_id
 * @property int|null $core_version_id
 * @property string $version_name
 * @property int $mini is this a Reduced / shortened version of a module?
 * @property int $question_count
 * @property string|null $published_at
 * @property bool $is_current
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CoreVersion|null $coreVersion
 * @property-read mixed $dropdown_label
 * @property-read mixed $file_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Modifier[] $modifiers
 * @property-read int|null $modifiers_count
 * @property-read \App\Models\Module $module
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsform[] $xlsforms
 * @property-read int|null $xlsforms_count
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereCoreVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereIsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereMini($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereQuestionCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleVersion whereVersionName($value)
 */
	class ModuleVersion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $name
 * @property string|null $embargo
 * @property int $global
 * @property string|null $authors
 * @property string|null $odk_central_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsform[] $xlsforms
 * @property-read int|null $xlsforms_count
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAuthors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereEmbargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOdkCentralId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Sdg
 *
 * @property string $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Module[] $modules
 * @property-read int|null $modules_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sdg newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sdg newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sdg query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sdg whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sdg whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sdg whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sdg whereUpdatedAt($value)
 */
	class Sdg extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Theme
 *
 * @property int $id
 * @property string|null $title
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Modifier[] $modifiers
 * @property-read int|null $modifiers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Module[] $modules
 * @property-read int|null $modules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsform[] $xlsforms
 * @property-read int|null $xlsforms_count
 * @method static \Illuminate\Database\Eloquent\Builder|Theme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Theme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Theme query()
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Theme whereUpdatedAt($value)
 */
	class Theme extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $jwt_token
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereJwtToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Xlsform
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $xlsfile
 * @property string|null $status
 * @property string|null $odk_central_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $project_id
 * @property string|null $draft_at when did it get first published as a draft
 * @property string|null $published_at when did it go live
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ModuleVersion[] $moduleVersions
 * @property-read int|null $module_versions_count
 * @property-read \App\Models\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Theme[] $themes
 * @property-read int|null $themes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform query()
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereDraftAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereOdkCentralId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereXlsfile($value)
 */
	class Xlsform extends \Eloquent {}
}

namespace App\Models\Xlsforms{
/**
 * App\Models\Xlsforms\ChoicesLabel
 *
 * @property int $id
 * @property int $xls_choices_row_id
 * @property string $type
 * @property string $language_id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Xlsforms\ChoicesRow $ChoicesRow
 * @property-read \App\Models\Language $language
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesLabel whereXlsChoicesRowId($value)
 */
	class ChoicesLabel extends \Eloquent {}
}

namespace App\Models\Xlsforms{
/**
 * App\Models\Xlsforms\ChoicesRow
 *
 * @property int $id
 * @property int|null $module_id
 * @property string $list_name
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsforms\ChoicesLabel[] $ChoicesLabels
 * @property-read int|null $choices_labels_count
 * @property-read \App\Models\Module|null $module
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesRow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesRow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesRow query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesRow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesRow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesRow whereListName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesRow whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesRow whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoicesRow whereUpdatedAt($value)
 */
	class ChoicesRow extends \Eloquent {}
}

namespace App\Models\Xlsforms{
/**
 * App\Models\Xlsforms\SurveyLabel
 *
 * @property int $id
 * @property int $xls_survey_row_id
 * @property string $type
 * @property string $language_id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Language $language
 * @property-read \App\Models\Xlsforms\SurveyRow $surveyRow
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel query()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyLabel whereXlsSurveyRowId($value)
 */
	class SurveyLabel extends \Eloquent {}
}

namespace App\Models\Xlsforms{
/**
 * App\Models\Xlsforms\SurveyRow
 *
 * @property int $id
 * @property int $module_id
 * @property string $type
 * @property string $name
 * @property string|null $constraint
 * @property string|null $required
 * @property string|null $appearance
 * @property string|null $default
 * @property string|null $relevant
 * @property string|null $repeat_count
 * @property string|null $read_only
 * @property string|null $calculation
 * @property string|null $choice_filter
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Module $module
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Xlsforms\SurveyLabel[] $surveyLabels
 * @property-read int|null $survey_labels_count
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow query()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereAppearance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereCalculation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereChoiceFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereConstraint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereReadOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereRelevant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereRepeatCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyRow whereUpdatedAt($value)
 */
	class SurveyRow extends \Eloquent {}
}

