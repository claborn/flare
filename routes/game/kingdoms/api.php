<?php

Route::get('/kingdoms/location', ['as' => 'kingdoms.location', 'uses' => 'Api\KingdomsController@getLocationData']);
Route::post('/kingdoms/{character}/settle', ['as' => 'kingdoms.settle', 'uses' => 'Api\KingdomsController@settle']);
Route::post('/kingdoms/{character}/upgrade-building/{building}', ['as' => 'kingdoms.building.upgrade', 'uses' => 'Api\KingdomsController@upgradeBuilding']);
Route::post('/kingdoms/building-upgrade/cancel', ['as' => 'kingdoms.building.queue.delete', 'uses' => 'Api\KingdomsController@removeBuildingFromQueue']);