<?php

$router->get('/test', function(){
    //create APP
    $newApp = new \App\M6Apps;
    $newApp->title = 'title';
    $newApp->description = 'description';
    $newApp->author = 'author';
    $newApp->app_type = 'app_type';
    $newApp->app_number = 'app_number';
    $newApp->iconLink = '';
    $newApp->prefix = '';

    $newApp->save();

    $newTab = new \App\AppTabs;
    $newTab->title = 'Home';
    $newTab->weight = 0;
    $newTab->app_id = $newApp->id;
    $newTab->order = date('u');
    $newTab->readOnly = true;
    $newTab->save();

    $newPanel = new \App\AppPanels;
    $newPanel->tab_id = $newTab->id;
    $newPanel->column = 0;
    $newPanel->weight = 0;
    $newPanel->title = 'Information';
    $newPanel->description = 'description';
    $newPanel->save();


    $newPanel = new \App\AppPanels;
    $newPanel->tab_id = $newTab->id;
    $newPanel->column = 1;
    $newPanel->weight = 0;
    $newPanel->title = 'Information';
    $newPanel->description = 'description';
    $newPanel->save();

    return $newApp;
});

$router->group( ['prefix' => 'api/v1', 'middleware' => 'auth:api'],
    function() use ($router) {

    }
);

$router->group( ['prefix' => 'api/file'], function () use ($router){
  // GetStream
  $router->post('/stream',    'AppCrudController@storeStreamFiles');
  $router->get('/post/{postId}', 'AppCrudController@getPostFileUrls');
  $router->get('/message/{messageId}', 'AppCrudController@getMessageFileUrls');
  // App builder
  $router->get('/url/{fileId}', 'AppCrudController@getFile');
  $router->post('/app-builder', 'AppCrudController@setFile');
  // S3Controller
  $router->post('/upload', 'S3Controller@createPresignedUrl');
  $router->post('/delete', 'S3Controller@deleteFile');
});

$router->post('api/get_url_data', 'HyperLinkController@getUrl');
// Get Token for GetStream Service
$router->post('/api/getGSToken', 'GSController@getToken');
$router->post('/api/getGSFeedToken', 'GSController@getFeedToken');
$router->get('/api/get_feed/{feedId}/{room}', 'GSController@getFeedActivity');
$router->get('/api/getUsers', 'UserController@getUsers');

$router->group(['prefix' => 'api/feed'], function() use ($router){
  $router->post('/activity', 'GSController@postActivity');
  $router->put('/activity', 'GSController@updateFeedActivity');
  $router->get('/activities/{room}/{foreignKey}', 'GSController@getGlobalActivities');
  // Feed Group
  $router->get('/room', 'GSGroupController@getFeedRoomsByUserToken');
  $router->post('/room', 'GSGroupController@storeFeedGroup');
  $router->put('/room/{id}', 'GSGroupController@updateFeedRoom');
  $router->delete('/room', 'GSGroupController@destroyFeedRoom');
  $router->post('/users', 'GSGroupController@updateUsersGroup');
});

// Get Token for Firebase
$router->get('/api/firebase/getToken', 'FirebaseController@getToken');

$router->get('/test/s3/storage', 'AppCrudController@testS3');

$router->group(['prefix' => 'api/search'],
    function() use ($router) {
        $router->post('unspcCodes', 'UnspcElasticController@index');
        $router->post('unspcCodes/ids', 'UnspcElasticController@getCodesByIds');

        $router->post('companyTypes', 'CompanyTypesElasticController@index');
        $router->post('companyTypes/ids', 'CompanyTypesElasticController@getCodesByIds');

        $router->post('regions', 'RegionsElasticController@index');
        $router->post('regions/ids', 'RegionsElasticController@getCodesByIds');

        $router->post('naicsCodes', 'NaicsCodesElasticController@index');
        $router->post('naicsCodes/ids', 'NaicsCodesElasticController@getCodesByIds');
    }
);

$router->group(['prefix' => 'api/filter'],function () use ($router) {
    $router->post('/apps', 'M6AppController@filterApps');
    $router->post('/builder-apps', 'M6AppController@filterBuilderApps');
    $router->post('/records', 'SearchController@filterRecords');
});


$router->group(['prefix' => 'api/cpm-import'],
    function() use ($router){
        $router->post('/parse', 'ImportCPMController@parseExcelCPM');
        $router->get('/parse/txt', 'ImportCPMController@parseTXTCPM');
    }
);

$router->group(['prefix' => 'api/field_values'], function () use ($router){
    $router->get('/simple/{redordID}/{fieldID}', 'AppCrudController@getValue');
    $router->post('/reference/{currentRecordID}/{referenceRecordID}/{fieldID}/{refID}', 'AppCrudController@getAndChangeRefFieldValue');
    $router->post('/by_panel/{recordID}/{panelID}', 'AppCrudController@getValues');
    $router->put('/single/{type}', 'AppCrudController@updateSigleValue');
    $router->put('/some', 'AppCrudController@storeFieldValues');
    $router->post('', 'AppCrudController@storeFieldValues');
    $router->delete('/single/{type}/{id}', 'AppCrudController@sampleValueDelete');
    $router->delete('/some', 'AppCrudController@deleteSomeValues');
    $router->delete('/all_by_field_id/{fieldId}', 'AppCrudController@deleteValuesByFieldId');
    $router->post('/fieldsByIds', 'AppCrudController@deleteValueByFieldIds');
});


$router->group(['prefix' => 'api/app-builder/'],
    function() use ($router) {
        $router->get('/app/list', 'AppBuilderController@getAllApps');
        $router->get('/app/{app}', 'AppBuilderController@getApp');
        $router->put('/app', 'AppBuilderController@updateApp');
        $router->delete('/app/{app}', 'AppBuilderController@deleteApp');

        $router->post('/tab/switch-order', 'AppBuilderController@switchOrderTabs');
        $router->post('/tab/list', 'AppBuilderController@listTabs');
        $router->post('/tab','AppBuilderController@storeTab');
        $router->post('/tab/{tab}','AppBuilderController@updateTab');
        $router->delete('/tab/{tab}','AppBuilderController@deleteTab');

        $router->post('/panel/list', 'AppBuilderController@listPanels');
        $router->post('/panel','AppBuilderController@storePanel');
        $router->post('/panel/{id}/copy', 'AppBuilderController@copyPanel');
        $router->put('/panel/{panel}','AppBuilderController@updatePanel');
        $router->post('/panel/{panelId}/move','AppBuilderController@movePanel');
        $router->delete('/panel/{panel}','AppBuilderController@deletePanel');

        $router->get('/app/fields/{app_id}', 'AppBuilderController@getFieldsByApp');
        $router->post('/field','AppBuilderController@storeField');
        $router->post('/fields','AppBuilderController@storeFields');
        $router->post('/field/list', 'AppBuilderController@listFields');
        $router->post('/field/list/all', 'AppBuilderController@listAllFields');
        $router->post('/field/{field}/update','AppBuilderController@updateField');
        $router->post('/field/{fieldId}/move','AppBuilderController@moveField');
        $router->delete('/field/{field}','AppBuilderController@deleteField');
        $router->put('/fields', 'AppBuilderController@updateListOfFields');

        $router->get('/record/{id}', 'AppBuilderController@getRecordById');
        $router->post('/record/list', 'AppBuilderController@listRecords');
        $router->post('/record','AppBuilderController@storeRecord');
        $router->put('/record/{record}','AppBuilderController@updateRecord');
        $router->delete('/record/{record}','AppBuilderController@deleteRecord');

        $router->post('/fieldValue/list', 'AppBuilderController@listfieldValues');
        $router->post('/fieldValue','AppBuilderController@storefieldValue');
        $router->post('/fieldValue/{fieldValue}','AppBuilderController@updatefieldValue');
        $router->post('/fieldValue/{fieldValue}','AppBuilderController@deletefieldValue');

        $router->get('/tab-add-order', 'AppBuilderController@AddOrderToExistingTabs');

        $router->post('/table-fields/get', 'AppBuilderController@GetTableFields');
        $router->put('/table-fields/update', 'AppBuilderController@UpdateTableFields');
        $router->post('/records_by_apps', 'AppCrudController@listOfRecordsByApp');

        $router->post('app_tables', 'AppTablesController@create');
        $router->put('app_tables/{table_id}', 'AppTablesController@update');
        $router->delete('app_tables/{table_id}','AppTablesController@delete');
        $router->get('app_tables/{table_id}/fields', 'AppTablesController@tableFields');
        $router->delete('app_tables/{table_id}', 'AppTablesController@deleteTable');

        $router->post('table_rows', 'AppTablesController@createRow');
        $router->delete('table_rows/{table_row_id}', 'AppTablesController@deleteRow');

        $router->get('table_rows/{table_id}/record/{recordID}', 'AppTablesController@getTableRowValues');
    }
);

$router->group(['prefix' => 'api/app_roles'],
    function() use ($router) {
        $router->get('/get_roles','RoleController@get');
        $router->post('/create','RoleController@create');
        $router->post('/update','RoleController@update');
        $router->post('/delete','RoleController@delete');
        $router->post('/saveRole','RoleController@saveAppRole');
        $router->post('/get_user_roles','RoleController@getUserAppRole');
        $router->post('/assign_role','RoleController@assignRole');
        $router->post('/get_user_taxonomies','RoleController@getUserTaxonomies');
    }
);

$router->group(['prefix' => 'api/auth'],
    function() use ($router) {
        $router->post('/signup',               'AuthController@signup');
        $router->post('/signin',               'AuthController@signin');
        $router->post('/confirmSignup',        'AuthController@confirmSignup');
        $router->post('/resendConfirmCode',    'AuthController@resendConfirmCode');
        $router->post('/startPasswordReset',   'AuthController@startPasswordReset');
        $router->post('/confirmPasswordReset', 'AuthController@confirmPasswordReset');
    }
);

$router->group(['prefix' => 'api/user'],
    function() use ($router) {
        $router->post('/', 'UserController@getUser'); // gets the dynamo user
        $router->post('/list', 'UserController@index');
        $router->put('/', 'UserController@update');
    }
);

$router->get( '/api/records/byApp/{appPrefix}', 'AppRecordsController@index');

$router->group([ 'prefix' => 'api/rapid' ],
    function() use ($router) {
        $router->get('/', 'RapidTicketController@index');
        $router->post('/ticket', 'RapidTicketController@create');
    }
);

$router->group(['prefix' => 'api/companies'],
    function () use ($router) {
        $router->get('/',             'CompaniesController@list');
        $router->put('/',             'CompaniesController@update');
        $router->get('/company/{id}', 'CompaniesController@getUsersByCompany');

        $router->put('/userCompany',     'UserCompanyController@update');
        $router->post('/userCompany',    'UserCompanyController@create');
        $router->put('/switchCompanies', 'UserCompanyController@switchCompanies');
    }
);

$router->group([ 'prefix' => 'api/m6codes' ],
    function () use ($router) {
        $router->post('/region/bulk', 'RegionsController@bulkUpload');
        $router->get('/region', 'RegionsController@index');

        $router->post('/naics/bulk', 'NaicsController@bulkUpload');
        $router->post('/naics', 'NaicsController@index');

        $router->post('/companyTypes/bulk', 'CompanyTypesController@bulkUpload');
        $router->post('/companyTypes', 'CompanyTypesController@index');

        $router->post('/unspc/bulk', 'UnspcsController@bulkUpload');
        $router->post('/unspc', 'UnspcsController@index');
    }
);

// M6Apps Work Order
    $router->group([ 'prefix' => 'api/work_order' ], function () use ($router) {
        $router->post   ('/', 'WorkActivityController@storeWork' );
        $router->post   ('/tasks_by_ids', 'WorkActivityController@showWorkByIds' );
        $router->get    ('/{userId}/{keyQuery}/{companyID}', 'WorkActivityController@showWorkByConsult' );
        $router->put    ('/{id}', 'WorkActivityController@updateWork' );
        $router->delete ('/{id}', 'WorkActivityController@destroy' );
        $router->get    ('/', 'WorkActivityController@getAllActions' );
    });
// M6Apps Work Assignments
    $router->group([ 'prefix' => 'api/wo_assignments/' ], function () use ($router) {
        $router->post   ('/',                  'WoAssignmentsController@storeAssignment'          );
        $router->get    ('/{column}/{value}',  'WoAssignmentsController@showAssignmentByConsult'  );
        $router->put    ('/{id}',              'WoAssignmentsController@updateAssignment'         );
        $router->delete ('/{id}',              'WoAssignmentsController@destroy'                  );
    });
// M6Apps
    $router->group(['prefix' => 'api/apps'], function () use ($router){
        $router->get('/',        'M6AppController@getAllApps');
        $router->get('/selects', 'M6AppController@selectApps');
        $router->get('/search',  'M6AppController@searchApps');
    });
// M6Records
    $router->get('/api/records/{type}', 'M6AppController@getRecords');
// M6APPS DynamicApps
    $router->group(['prefix' => 'api/dynamic_apps'], function () use ($router){
        $router->get ('', 'M6AppController@allDynamicRecords');
        $router->get ('/apps', 'M6AppController@allDynamicApps');
        $router->get ('/by/{id}', 'M6AppController@DynamicAppsByID');
        $router->post('', 'M6AppController@postDynamicApp');
    });

// M6Apps ITApps
    $router->group([ 'prefix' => 'api/itapps'], function () use ($router) {
        $router->get('/', 'M6AppController@allItApps');
        $router->get('/{id}', 'M6AppController@showByID');
        $router->get('/specifi/{column}/{value}', 'M6AppController@showItAppsByConsult');
        $router->put('/{id}', 'M6AppController@updateITApp');
        $router->delete('/{id}', 'M6AppController@destroy');
        // ITApp Info
        $router->get('/get_itapp_info/{id}', 'M6AppController@getAllITAppInfo');
        // Specification Info
        $router->get('/get_specification_info/{id}', 'M6AppController@getSpecificationInfo');
        // Installation Info
        $router->get('/get_install_info/{id}', 'M6AppController@getAllInstallInfo');
        // Rationalization Info
        $router->get('/get_rationalization_info/{id}', 'M6AppController@getAllRationalizationInfo');
        // Put all Info
        $router->put('/update_all_info/{id}', 'M6AppController@updateAllInfo');
    });
    $router->post('/api/record/itapp', 'M6AppController@postItApp');

// Taxonomy vocabulary and terms
$router->group(['prefix' => 'api/taxonomy'], function () use ($router){
    $router->get ('/vocabulary', 'TaxonomyController@getVocabularies');
    $router->get ('/terms/{vocabularyId}', 'TaxonomyController@getTerms');
    $router->get ('/terms/get/all', 'TaxonomyController@getAllTerms');
    $router->get ('/term/{termId}', 'TaxonomyController@getTerm');
    $router->delete('/vocabulary/{id}', 'TaxonomyController@removeVocabulary');
    $router->delete('/terms/{id}', 'TaxonomyController@removeTerm');
    $router->put('/vocabulary/{id}', 'TaxonomyController@updateVocabulary');
    $router->put('/terms/{id}', 'TaxonomyController@updateTerm');
    $router->post('/vocabulary', 'TaxonomyController@createVocabulary');
    $router->post('/terms', 'TaxonomyController@createTerm');
});

// App Info General
    $router->post('/api/app_info_general', 'AppInfoGeneralController@storeAppInfoGeneral');
    $router->get('/api/app_info_general/{appID}', 'AppInfoGeneralController@showByAppID');
    $router->put('/api/app_info_general/{id}', 'AppInfoGeneralController@updateAppInfoGeneral');
    $router->delete('/api/app_info_general/{id}', 'AppInfoGeneralController@destroy');
// App Image
    $router->post('/api/app_image', 'ImageController@storeImage');
    $router->get('/api/app_image/{appID}', 'ImageController@showByAppID');
    $router->put('/api/app_image/{id}', 'ImageController@updateImage');
    $router->delete('/api/app_image/{id}', 'ImageController@destroy');
// App Information Security
    $router->post('/api/information_security', 'InformationSecurityController@storeInformationSecurity');
    $router->get('/api/information_security/{appID}', 'InformationSecurityController@showByAppID');
    $router->put('/api/information_security/{id}', 'InformationSecurityController@updateInformationSecurity');
    $router->delete('/api/information_security/{id}', 'InformationSecurityController@destroy');
// AppsSettings
    $router->get('/api/apps_settings/per_type/{appType}', 'AppsSettingsController@index');
    $router->get('/api/apps_settings/m6works/{appId}/activities', 'AppsSettingsController@getAppActivities');
    $router->get('/api/apps_settings/per_id/{id}', 'AppsSettingsController@showByID');
    $router->get('/api/apps_settings/specifi/{column}/{value}', 'AppsSettingsController@showAppsSettingsByConsult');
    $router->get('/api/apps_settings/per_param/{column}', 'AppsSettingsController@showAppsSettingsByParams');
    $router->post('/api/apps_settings', 'AppsSettingsController@store');
    $router->post('/api/apps_settings/itapp', 'AppsSettingsController@storeAppSettingItapp');
    $router->put('/api/apps_settings/{id}', 'AppsSettingsController@updateAppSetting');
    $router->delete('/api/apps_settings/{id}', 'AppsSettingsController@destroy');
// Tags
    $router->get('/api/tag', 'TagsController@index');
    $router->get('/api/tag/{id}', 'TagsController@showByID');
    $router->get('/api/tag/specifi/{column}/{value}', 'TagsController@showTagsByConsult');
    $router->post('/api/tag', 'TagsController@storeTag');
    $router->post('/api/some_tags', 'TagsController@storeSomeTags');
    $router->put('/api/some_tags', 'TagsController@updateSomeTags');
    $router->put('/api/tag/{id}', 'TagsController@updateTag');
    $router->delete('/api/tag/{id}', 'TagsController@destroy');
// Configuration
    $router->get('/api/config', 'ConfigurationController@index');
    $router->get('/api/config/{id}', 'ConfigurationController@showByID');
    $router->post('/api/config', 'ConfigurationController@storeConfig');
    $router->put('/api/config/{id}', 'ConfigurationController@updateConfig');
    $router->delete('/api/config/{id}', 'ConfigurationController@destroy');
// Notifications
    $router->get('/api/notifications', 'NotificationController@index');
    $router->get('/api/notification/{id}', 'NotificationController@showByID');
    $router->get('/api/notifications/{appId}', 'NotificationController@showByAppID');
    $router->post('/api/notification', 'NotificationController@storeNotification');
    $router->put('/api/notification/{id}', 'NotificationController@updateNotification');
    $router->delete('/api/notification/{id}', 'NotificationController@destroy');
// Notification Date
    $router->get('/api/notification_date/{id}', 'NotificationDateController@showByID');
    $router->post('/api/notification_date', 'NotificationDateController@storeNotificationDate');
    $router->put('/api/notification_date/{id}', 'NotificationDateController@updateNotificationDate');
    $router->delete('/api/notification_date/{id}', 'NotificationDateController@destroy');
// Contact Notification
    $router->get('/api/contact_notification/specifi/{column}/{value}', 'ContactNotificationController@showContactNotificationByConsult');
    $router->get('/api/contact_notification/{id}', 'ContactNotificationController@showByID');
    $router->post('/api/contact_notification', 'ContactNotificationController@storeContactNotification');
    $router->put('/api/contact_notification/{id}', 'ContactNotificationController@updateContactNotification');
    $router->delete('/api/contact_notification/{id}', 'ContactNotificationController@destroy');
// Contracts
    $router->get('/api/contract/{appID}', 'ContractController@showByAppID');
    $router->post('/api/contract', 'ContractController@storeContract');
    $router->put('/api/contract/{id}', 'ContractController@updateContract');
    $router->delete('/api/contract/{id}', 'ContractController@destroy');
// Dependencies
    $router->get('/api/dependencie', 'DependencieController@index');
    $router->get('/api/dependencie/{appID}', 'DependencieController@showByAppID');
    $router->post('/api/dependencie', 'DependencieController@storeDependencie');
    $router->put('/api/dependencie/{id}', 'DependencieController@updateDependencies');
    $router->delete('/api/dependencie/{id}', 'DependencieController@destroy');
// Specification Certification
    $router->get('/api/specification_certification/{appID}', 'SpecificationCertificationController@showByAppID');
    $router->post('/api/specification_certification', 'SpecificationCertificationController@storeSpecificationCertification');
    $router->put('/api/specification_certification/{id}', 'SpecificationCertificationController@updateSpecificationCertification');
    $router->delete('/api/specification_certification/{id}', 'SpecificationCertificationController@destroy');
// Specification Maintenance
    $router->get('/api/specification_maintenance/{appID}', 'SpecificationMaintenanceController@showByAppID');
    $router->post('/api/specification_maintenance', 'SpecificationMaintenanceController@storeSpecificationMaintenance');
    $router->put('/api/specification_maintenance/{id}', 'SpecificationMaintenanceController@updateSpecificationMaintenance');
    $router->delete('/api/specification_maintenance/{id}', 'SpecificationMaintenanceController@destroy');
// Specification Monitoring
    $router->get('/api/specification_monitoring/{appID}', 'SpecificationMonitoringController@showByAppID');
    $router->post('/api/specification_monitoring', 'SpecificationMonitoringController@storeSpecificationMonitoring');
    $router->put('/api/specification_monitoring/{id}', 'SpecificationMonitoringController@updateSpecificationMonitoring');
    $router->delete('/api/specification_monitoring/{id}', 'SpecificationMonitoringController@destroy');
// Installation Attachments
    $router->get('/api/install_attachments/{appID}', 'InstallationAttachmentsController@showByAppID');
    $router->post('/api/install_attachments', 'InstallationAttachmentsController@storeIstallAttachment');
    $router->put('/api/install_attachments/{id}', 'InstallationAttachmentsController@updateInstallationAttachments');
    $router->delete('/api/install_attachments/{id}', 'InstallationAttachmentsController@destroy');
// Installation Aditional Information
    $router->get('/api/install_aditional_info/{appID}', 'InstallationAditionalInformationController@showByAppID');
    $router->post('/api/install_aditional_info', 'InstallationAditionalInformationController@storeAditionalInformation');
    $router->put('/api/install_aditional_info/{id}', 'InstallationAditionalInformationController@updateInstallationAditionalInformation');
    $router->delete('/api/install_aditional_info/{id}', 'InstallationAditionalInformationController@destroy');
// Installation Support
    $router->get('/api/install_aditional_info/{appID}', 'InstallationAditionalInformationController@showByAppID');
    $router->post('/api/install_aditional_info', 'InstallationAditionalInformationController@storeInstallSupport');
    $router->put('/api/install_aditional_info/{id}', 'InstallationAditionalInformationController@updateInstallationSupport');
    $router->delete('/api/install_aditional_info/{id}', 'InstallationAditionalInformationController@destroy');
// Installation General
    $router->get('/api/install_general/{appID}', 'InstallationGeneralController@showByAppID');
    $router->post('/api/install_general', 'InstallationGeneralController@storeInstallGeneral');
    $router->put('/api/install', 'InstallationGeneralController@updateInstallation');
    $router->put('/api/install_general/{id}', 'InstallationGeneralController@updateInstallationGenerals');
    $router->delete('/api/install_general/{id}', 'InstallationGeneralController@destroy');
// Licensing
    $router->get('/api/licensing', 'LicensingController@index');
    $router->get('/api/licensing/{appID}', 'LicensingController@showByAppID');
    $router->post('/api/licensing', 'LicensingController@storeLicensing');
    $router->put('/api/licensing/{id}', 'LicensingController@updateLicensing');
    $router->delete('/api/licensing/{id}', 'LicensingController@destroy');
// Rationalization Costs
    $router->get('/api/rationalization_costs/{appID}', 'RationalizationCostController@showByAppID');
    $router->post('/api/rationalization_costs', 'RationalizationCostController@storeRationalizationCost');
    $router->put('/api/rationalization_costs/{id}', 'RationalizationCostController@updateRationalizationCost');
    $router->delete('/api/rationalization_costs/{id}', 'RationalizationCostController@destroy');
// Rationalization Licensing
    $router->get('/api/rationalization_licensing/{appID}', 'RationalizationLicensingController@showByAppID');
    $router->post('/api/rationalization_licensing', 'RationalizationLicensingController@storeRationalizationLicensing');
    $router->put('/api/rationalization_licensing/{id}', 'RationalizationLicensingController@updateRationalizationLicensing');
    $router->delete('/api/rationalization_licensing/{id}', 'RationalizationLicensingController@destroy');
// Rationalization FTE
    $router->get('/api/rationalization_fte/{appID}', 'RationalizationFTEController@showByAppID');
    $router->post('/api/rationalization_fte', 'RationalizationFTEController@storeRationalizationFte');
    $router->put('/api/rationalization_fte/{id}', 'RationalizationFTEController@updateRationalizationFte');
    $router->delete('/api/rationalization_fte/{id}', 'RationalizationFTEController@destroy');
// Rationalization Users
    $router->get('/api/rationalization_user/{appID}', 'RationalizationUsersController@showByAppID');
    $router->post('/api/rationalization_user', 'RationalizationUsersController@storeRationalizationUsers');
    $router->put('/api/rationalization_user/{id}', 'RationalizationUsersController@updateRationalizationUsers');
    $router->delete('/api/rationalization_user/{id}', 'RationalizationUsersController@destroy');
// Rationalization Governance
    $router->get('/api/rationalization_governance/{appID}', 'RationalizationGovernanceController@showByAppID');
    $router->post('/api/rationalization_governance', 'RationalizationGovernanceController@storeRationalizationGovernance');
    $router->put('/api/rationalization_governance/{id}', 'RationalizationGovernanceController@updateRationalizationGovernance');
    $router->delete('/api/rationalization_governance/{id}', 'RationalizationGovernanceController@destroy');
// Rationalization Attribute
    $router->get('/api/rationalization_attribute/{appID}', 'RationalizationAttributesController@showByAppID');
    $router->put('/api/rationalization_attribute/{id}', 'RationalizationAttributesController@updaterationalizationAttribute');

// Marketplace
    $router->get('/api/marketplaces', 'MarketplaceController@getMarketplaces');
    $router->get('/api/marketplaces/{id}', 'MarketplaceController@getMarketplace');
    $router->post('/api/marketplaces', 'MarketplaceController@createMarketplace');
    $router->put('/api/marketplaces/{id}', 'MarketplaceController@updateMarketplace');
    $router->delete('/api/marketplaces/{id}', 'MarketplaceController@deleteMarketplace');
    $router->post('/api/marketplaces/{marketplaceId}/media', 'MarketplaceController@addMarketplaceMedia');
    $router->delete('/api/marketplaces/{marketplaceId}/media/{mediaId}', 'MarketplaceController@deleteMarketplaceMedia');
