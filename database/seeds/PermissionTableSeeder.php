<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['entity_name' => 'Clients',            'permission' => 'view_client',              'display_name' => 'Can View Client',                    'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Clients',            'permission' => 'add_client',               'display_name' => 'Can Add Client',                     'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Clients',            'permission' => 'edit_client',              'display_name' => 'Can Edit Client',                    'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Clients',            'permission' => 'delete_client',            'display_name' => 'Can Delete Client',                  'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Chains',             'permission' => 'view_chain',               'display_name' => 'Can View Chain',                     'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Chains',             'permission' => 'add_chain',                'display_name' => 'Can Add Chain',                      'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Chains',             'permission' => 'edit_chain',               'display_name' => 'Can Edit Chain',                     'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Chains',             'permission' => 'delete_chain',             'display_name' => 'Can Delete Chain',                   'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Sites',              'permission' => 'view_site',                'display_name' => 'Can View Site',                      'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Sites',              'permission' => 'add_site',                 'display_name' => 'Can Add Site',                       'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Sites',              'permission' => 'edit_site',                'display_name' => 'Can Edit Site',                      'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Sites',              'permission' => 'delete_site',              'display_name' => 'Can Delete Site',                    'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Fieldrep Org',       'permission' => 'view_fieldreporg',         'display_name' => 'Can View Fieldrep Org',              'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Fieldrep Org',       'permission' => 'add_fieldreporg',          'display_name' => 'Can Add Fieldrep Org',               'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Fieldrep Org',       'permission' => 'edit_fieldreporg',         'display_name' => 'Can Edit Fieldrep Org',              'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Fieldrep Org',       'permission' => 'delete_fieldreporg',       'display_name' => 'Can Delete Fieldrep Org',            'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Fieldreps',          'permission' => 'view_fieldrep',            'display_name' => 'Can View Fieldreps',                 'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Fieldreps',          'permission' => 'add_fieldrep',             'display_name' => 'Can Add Fieldreps',                  'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Fieldreps',          'permission' => 'edit_fieldrep',            'display_name' => 'Can Edit Fieldreps',                 'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Fieldreps',          'permission' => 'delete_fieldrep',          'display_name' => 'Can Delete Fieldreps',               'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Survey Template',    'permission' => 'view_surveytemplates',     'display_name' => 'Can Add Survey Template',            'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Survey Template',    'permission' => 'add_surveytemplates',      'display_name' => 'Can Add Survey Template',            'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Survey Template',    'permission' => 'edit_surveytemplates',     'display_name' => 'Can Add Survey Template',            'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Survey Template',    'permission' => 'delete_surveytemplates',   'display_name' => 'Can Add Survey Template',            'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Projects',           'permission' => 'view_project',             'display_name' => 'Can View Projects',                  'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Projects',           'permission' => 'add_project',              'display_name' => 'Can Add Projects',                   'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Projects',           'permission' => 'edit_project',             'display_name' => 'Can Edit Projects',                  'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Projects',           'permission' => 'delete_project',           'display_name' => 'Can Delete Projects',                'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Rounds',             'permission' => 'view_round',               'display_name' => 'Can View Rounds',                    'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Rounds',             'permission' => 'add_round',                'display_name' => 'Can Add Rounds',                     'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Rounds',             'permission' => 'edit_round',               'display_name' => 'Can Edit Rounds',                    'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Rounds',             'permission' => 'delete_round',             'display_name' => 'Can Delete Rounds',                  'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Assignments',        'permission' => 'view_assignment',          'display_name' => 'Can View Assignments',               'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Assignments',        'permission' => 'add_assignment',           'display_name' => 'Can Create Assignments',             'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Assignments',        'permission' => 'edit_assignment',          'display_name' => 'Can Edit Assignments',               'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Assignments',        'permission' => 'delete_assignment',        'display_name' => 'Can Delete Assignments',             'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Assignments',        'permission' => 'schedule_assignment',      'display_name' => 'Can Schedule Assignments',           'order' => 5, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Assignments',        'permission' => 'offer_assignment',         'display_name' => 'Can Offer Assignments',              'order' => 6, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Surveys',            'permission' => 'view_survey',              'display_name' => 'Can View Survey List',               'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Surveys',            'permission' => 'review_surveys',           'display_name' => 'Can Review Survey',                  'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Surveys',            'permission' => 'edit_surveys',             'display_name' => 'Can Edit Survey',                    'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Surveys',            'permission' => 'approve_surveys',          'display_name' => 'Can Approve Survey',                 'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Surveys',            'permission' => 'reject_surveys',           'display_name' => 'Can Rejected Survey',                'order' => 5, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Surveys',            'permission' => 'partial_surveys',          'display_name' => 'Can Mark Survey as Not Approved',    'order' => 6, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Import',             'permission' => 'import_clients',           'display_name' => 'Can Import Clients',                 'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Import',             'permission' => 'import_chains',            'display_name' => 'Can Import Chains',                  'order' => 2, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Import',             'permission' => 'import_fieldreps',         'display_name' => 'Can Import Fieldreps',               'order' => 3, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Import',             'permission' => 'import_sites',             'display_name' => 'Can Import Sites',                   'order' => 4, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Import',             'permission' => 'import_fieldreporgs',      'display_name' => 'Can Import Fieldrep Organization',   'order' => 5, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Import',             'permission' => 'import_assignments',       'display_name' => 'Can Import Assignments',             'order' => 6, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Import',             'permission' => 'import_prefbans',          'display_name' => 'Can Import PrefBans',                'order' => 7, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
            ['entity_name' => 'Export',             'permission' => 'export_survey',            'display_name' => 'Can Export Survey',                  'order' => 1, 'created_at' => \Carbon::now(), 'updated_at' => \Carbon::now()],
        ]);
    }
}
