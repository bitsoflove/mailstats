<?php

Route::bind('projectSlug', function ($projectSlug) {
    $project = \BitsOfLove\MailStats\Entities\Project::where('name', $projectSlug)->first();

    if ($project instanceof \BitsOfLove\MailStats\Entities\Project) {
        return $project;
    }

    throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
});

Route::group(['middleware' => []], function () {
    // for testing purposes only
    Route::get('mail-statistics/test-entry-point', "BitsOfLove\\MailStats\\Http\\Controllers\\MailStatisticsController@testEntryPoint");

    Route::get("mail-statistics", "BitsOfLove\\MailStats\\Http\\Controllers\\MailStatisticsController@index");
    Route::get("mail-statistics/{projectSlug}", [
        'as' => "mail-stats-per-project",
        'uses' => "BitsOfLove\\MailStats\\Http\\Controllers\\MailStatisticsController@indexPerProject"
    ]);
    Route::get("mail-statistics/{projectSlug}/charts", [
        'as' => "mail-stats-per-project-cart",
        'uses' => "BitsOfLove\\MailStats\\Http\\Controllers\\MailStatisticsController@charts"
    ]);
    Route::get("mail-statistics/{projectSlug}/{messageId}", [
        'as' => "mail-stats-per-message-id",
        'uses' => "BitsOfLove\\MailStats\\Http\\Controllers\\MailStatisticsController@indexPerMessageId"
    ]);

    Route::get('projects/{projects}/delete', [
        'as' => "projects.delete",
        'uses' => "BitsOfLove\\MailStats\\Http\\Controllers\\ProjectsController@delete"
    ]);
    Route::resource('projects', "BitsOfLove\\MailStats\\Http\\Controllers\\ProjectsController", [
        'except' => ['show']
    ]);
});

Route::group(['middleware' => []], function () {
    // route to receive a response from mailgun
    Route::post('mail-statistics', "BitsOfLove\\MailStats\\Http\\Controllers\\MailsController@log");
    // route to send an email
    Route::post('mail-send', "BitsOfLove\\MailStats\\Http\\Controllers\\MailsController@mail");
});
