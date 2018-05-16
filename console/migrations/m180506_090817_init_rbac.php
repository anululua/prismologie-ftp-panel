<?php

use yii\db\Migration;

/**
 * Class m180506_090817_init_rbac
 */
class m180506_090817_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $viewUtilities = $auth->createPermission('viewUtilities');
        $viewUtilities->description = 'View files and folders';
        $auth->add($viewUtilities);
        
        $downloadUtilities = $auth->createPermission('downloadUtilities');
        $downloadUtilities->description = 'Download files';
        $auth->add($downloadUtilities);
        
        $manageUtilities = $auth->createPermission('manageUtilities');  //add, edit, delete utilities
        $manageUtilities->description = 'Manage utilities';
        $auth->add($manageUtilities);
        
        $manageUsers = $auth->createPermission('manageUsers');  //add, edit, delete, view, activate, deactivate users
        $manageUsers->description = 'Manage users';
        $auth->add($manageUsers);
        
        $assignUsers = $auth->createPermission('assignUsers');  //assign access to moderators for folders or utilities
        $assignUsers->description = 'Assign users access permissions';
        $auth->add($assignUsers);

        $public = $auth->createRole('public');
        $public->description = 'General Public canonly view or download utilities';
        $auth->add($public);
        $auth->addChild($public,$viewUtilities);
        $auth->addChild($public,$downloadUtilities);
        
        $moderator = $auth->createRole('moderator');
        $moderator->description = 'Moderator has limited permission to acces manage folders and utilities';
        $auth->add($moderator);
        $auth->addChild($moderator, $manageUtilities);
        $auth->addChild($moderator, $public);

        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator  has complte access. Can manage the users and assign the Moderators access to folders';
        $auth->add($admin);
        $auth->addChild($admin, $manageUsers);
        $auth->addChild($admin, $assignUsers);
        $auth->addChild($admin, $moderator);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180506_090817_init_rbac cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {


    }
    */
}
