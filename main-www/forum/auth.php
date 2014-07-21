    <?php
    define('IN_PHPBB', true);
    $phpbb_root_path = $_SERVER['DOCUMENT_ROOT'].'/forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    require($phpbb_root_path . 'common.' . $phpEx);
    require($phpbb_root_path . 'includes/functions_user.' . $phpEx);
    require($phpbb_root_path . 'includes/functions_module.' . $phpEx);

    $mode = "login";

    if (in_array($mode, array('login', 'logout', 'confirm', 'sendpassword', 'activate')))
    {
        define('IN_LOGIN', true);
    }

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup('ucp');

    // Setting a variable to let the style designer know where he is...
    $template->assign_var('S_IN_UCP', true);

    $module = new p_master();
    $default = false; 
    


    function loginForum()
    {
        login_box('/domjudge/public');
    }

    function logoutForum()
    {
        redirect("/forum/ucp.php?mode=logout");
    }
    ?>