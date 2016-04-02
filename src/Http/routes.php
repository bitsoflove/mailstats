<?php

/**
 * Route model binding
 */
Route::bind('projectSlug', function ($projectSlug) {
    $project = \BitsOfLove\MailStats\Entities\Project::where('name', $projectSlug)->first();

    if ($project instanceof \BitsOfLove\MailStats\Entities\Project) {
        return $project;
    }

    throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
});

Route::group(['middleware' => []], function () {
    // for testing purposes only
    Route::get('mail-statistics/test-entry-point', "MailStatisticsController@testEntryPoint");

    Route::get("mail-statistics", "MailStatisticsController@index");
    Route::get("mail-statistics/{projectSlug}", [
        'as' => "mail-stats-per-project",
        'uses' => "MailStatisticsController@indexPerProject"
    ]);
    Route::get("mail-statistics/{projectSlug}/charts", [
        'as' => "mail-stats-per-project-cart",
        'uses' => "MailStatisticsController@charts"
    ]);
    Route::get("mail-statistics/{projectSlug}/{messageId}", [
        'as' => "mail-stats-per-message-id",
        'uses' => "MailStatisticsController@indexPerMessageId"
    ]);

    Route::get('projects/{projects}/delete', [
        'as' => "projects.delete",
        'uses' => "ProjectsController@delete"
    ]);
    Route::resource('projects', "ProjectsController", [
        'except' => ['show']
    ]);
});

Route::group(['middleware' => []], function () {
    // route to receive a response from mailgun
    Route::post('mail-statistics', "MailsController@log");
    // route to send an email
    Route::post('mail-send', "MailsController@mail");
});
