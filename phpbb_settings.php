<?php

// not for directly open
if (! defined('IN_ADMIN'))
{
    exit;
}


//current case
$current_case = g('case', 'str', 'view');

//current template
$stylee = 'admin_phpbb_settings';

// template variables
$styleePath = dirname(__FILE__);
$action     = basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php');

$H_FORM_KEYS	     = kleeja_add_form_key('adm_phpbb_settings');
$H_FORM_KEYS_GET	 = kleeja_add_form_key_get('adm_phpbb_settings');


switch ($current_case)
{
    /**
     * show a list of current ftp accounts
     */
    default:
    case 'view':
        $kjauth_file_content = str_replace('{___API_KEY___}', $config['phpbb_intr_api_key'], file_get_contents(__DIR__ . '/kjauth.php'));

        break;

    /**
     * test connection
     */
    case 'test':

        if (! kleeja_check_form_key_get('adm_phpbb_settings'))
        {
            header('HTTP/1.1 405 Method Not Allowed');
            $adminAjaxContent = $lang['INVALID_FORM_KEY'];
        }
        else
        {
            $phpbb_folder_path = PATH . rtrim($config['phpbb_intr_path'], '/') . '/';

            if (file_exists($phpbb_folder_path . 'phpbb/user.php'))
            {
                @file_put_contents($phpbb_folder_path . 'kjauth.php',
                    str_replace('{___API_KEY___}', $config['phpbb_intr_api_key'], file_get_contents(__DIR__ . '/kjauth.php'))
                );
            }

            $adminAjaxContent = phpbb_auth_test() !== false ? 'done' : 'none';
        }

        break;

    case 'regenerate':

        if (! kleeja_check_form_key_get('adm_phpbb_settings'))
        {
            header('HTTP/1.1 405 Method Not Allowed');
            kleeja_admin_err($lang['INVALID_FORM_KEY'], $action);
        }
        else
        {
            update_config('phpbb_intr_api_key', sha1(mt_rand()));

            $phpbb_folder_path = PATH . rtrim($config['phpbb_intr_path'], '/') . '/';

            if (file_exists($phpbb_folder_path . 'phpbb/user.php'))
            {
                @file_put_contents($phpbb_folder_path . 'kjauth.php',
                    str_replace('{___API_KEY___}', $config['phpbb_intr_api_key'], file_get_contents(__DIR__ . '/kjauth.php'))
                );
            }

            kleeja_admin_info(sprintf($lang['ITEM_UPDATED'], $olang['PHPBB_INTR_API_KEY']), $action);
        }

        break;


    case 'update':

        if (! kleeja_check_form_key('adm_phpbb_settings', 1000))
        {
            header('HTTP/1.1 405 Method Not Allowed');
            $adminAjaxContent = $lang['INVALID_FORM_KEY'];
        }
        else
        {
            $list  = [
                'phpbb_intr_enabled',
                'phpbb_intr_path',
                'phpbb_intr_link',
            ];

            if (! ip('phpbb_intr_enabled'))
            {
                $_POST['phpbb_intr_enabled'] = 0;
            }


            foreach ($list as $item)
            {
                update_config($item, p($item, 'str'));
            }

            delete_cache('data_config');

            $adminAjaxContent = 'done';
        }

        break;
}
