<?php

class AdminController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // special authentication check for the entire controller: Note the check-ADMIN-authentication!
        // All methods inside this controller are only accessible for admins (= users that have role type 7)
        Auth::checkAdminAuthentication();
    }

    /**
     * This method controls what happens when you move to /admin or /admin/index in your app.
     */
    public function index()
    {
        $this->View->render('admin/index', array(
                'users' => UserModel::getPublicProfilesOfAllUsers(),
                'groups' => GroupModel::getAllGroups())
        );
    }

    /**
     * Handles admin updates for user acounts indclusing:
     * - changing user group
     * - settings sustepnsion time
     * - soft deleting user accounts
     * @return void
     */
    public function actionAccountSettings()
    {
        AdminModel::setAccountSuspensionAndDeletionStatus(
            Request::post('suspension'), 
            Request::post('softDelete'), 
            Request::post('user_id')
        );

        AdminModel::updateUserGroup(
            Request::post('user_id'),
            Request::post('user_account_type')
        );

        Redirect::to("admin");
    }
}
