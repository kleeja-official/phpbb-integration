<?php
// kleeja plugin
// developer: kleeja team

// prevent illegal run
if (! defined('IN_PLUGINS_SYSTEM'))
{
    exit;
}


// plugin basic information
$kleeja_plugin['phpbb_integration']['information'] = [
    // the casual name of this plugin, anything can a human being understands
    'plugin_title' => [
        'en' => 'phpBB Integration',
        'ar' => 'التكامل مع phpBB'
    ],
    // who wrote this plugin?
    'plugin_developer' => 'kleeja.com',
    // this plugin version
    'plugin_version' => '1.0.4',
    // explain what is this plugin, why should i use it?
    'plugin_description' => [
        'en' => 'phpBB Membership Integration',
        'ar' => 'التكامل مع عضويات phpBB'
    ],
    //settings page, if there is one (what after ? like cp=j_plugins)
    'settings_page' => 'cp=phpbb_settings',
    // min version of kleeja that's required to run this plugin
    'plugin_kleeja_version_min' => '3.1.0',
    // max version of kleeja that support this plugin, use 0 for unlimited
    'plugin_kleeja_version_max' => '3.9.9',
    // should this plugin run before others?, 0 is normal, and higher number has high priority
    'plugin_priority' => 0
];

//after installation message, you can remove it, it's not requiered
$kleeja_plugin['phpbb_integration']['first_run']['ar'] = '
يجب ضبط إعدادات الإضافة من خلال صفحة الإضافة لضمان عملها وتفعيلها <br><br>
شكراً لاستخدامك الإضافة، قم بمراسلتنا بالأخطاء عند ظهورها على: <br>
https://github.com/kleeja-official/kleeja/issues
';

$kleeja_plugin['phpbb_integration']['first_run']['en'] = '
In order for this plugin to works, you need to adjust its settings from its page.<Br><br>
Thank you for using our plugin. If you encounter any bugs and errors, report them on: <br>
https://github.com/kleeja-official/kleeja/issues
';


// plugin installation function
$kleeja_plugin['phpbb_integration']['install'] = function ($plg_id) {
    //new options
    $options = [
        'phpbb_intr_enabled' =>
        [
            'value'  => '0',
            'plg_id' => $plg_id,
            'type'   => 'phpbb_integration'
        ],
        'phpbb_intr_path' =>
        [
            'value'  => '../phpbb',
            'plg_id' => $plg_id,
            'type'   => 'phpbb_integration'
        ],
        'phpbb_intr_link' =>
        [
            'value'  => 'http://example.com/phpbb',
            'plg_id' => $plg_id,
            'type'   => 'phpbb_integration'
        ],
        'phpbb_intr_api_key' =>
        [
            'value'  => sha1(mt_rand()),
            'plg_id' => $plg_id,
            'type'   => 'phpbb_integration'
        ],
    ];


    add_config_r($options);


    //new language variables
    add_olang([
        'R_PHPBB_SETTINGS'                 => 'إعدادات phpBB',
        'PHPBB_INTR_ENABLE'                => 'تفعيل التكامل مع عضويات phpBB',
        'PHPBB_INTR_PATH'                  => 'مسار مجلد phpBB بالنسبة لمجلد كليجا',
        'PHPBB_INTR_PATH_EXP'              => 'غير اجباري, سنستخدمه لمحاولة نسخ ملف kjauth.php لمجلد المنتدى المربوط به إن كان في نفس الإستضافة.',
        'PHPBB_INTR_LINK'                  => 'رابط منتدى phpBB',
        'PHPBB_INTR_API_KEY'               => 'مفتاح الربط العشوائي',
        'PHPBB_INTR_KJAUTH_FILE_EXP'       => 'قم بنسخ المحتويات ووضعها في ملف باسم kjauth.php في مجلد المنتدى المربوط فيه.',
        'PHPBB_INTR_REGENERATE'            => 'إعادة إنشاء مفتاح الربط',
        'PHPBB_INTR_KJAUTH_FILE'           => 'إنشاء ملف kjauth.php',
        'PHPBB_INTR_REGENERATE_NOTE'       => 'هل أنت متأكد؟ عملية إعادة الإنشاء للمفتاح تتطلب منك نسخ المفتاح ووضفعه في ملف kjauth.php من جديد!',
        'PHPBB_INTR_TEST'                  => 'فحص التكامل',
        'PHPBB_INTR_TEST_NOTE'             => 'لتفعيل التكامل قم بعمل فحص للتكامل.',
        'PHPBB_INTR_TEST_NOTE_ERR'         => 'فشل الإتصال، قم بالتأكد من الإعدادات قبل التجربة مرة أخرى!',
        'PHPBB_INTR_TEST_NOTE_SUCCESS'     => 'الاتصال ناجح! يمكنك الآن تفعيل التكامل لو أردت ذلك.',
    ],
        'ar',
        $plg_id);

    add_olang([
        'R_PHPBB_SETTINGS'                 => 'phpBB Intergration',
        'PHPBB_INTR_ENABLE'                => 'Enable phpBB membership Integration',
        'PHPBB_INTR_PATH'                  => 'phpBB relative path',
        'PHPBB_INTR_PATH_EXP'              => 'Optional, we will try to copy kjauth.php to phpbb folder if in same hosting space.',
        'PHPBB_INTR_LINK'                  => 'Link to phpBB forum',
        'PHPBB_INTR_API_KEY'               => 'Integration generated key',
        'PHPBB_INTR_KJAUTH_FILE_EXP'       => 'With this content, create a file called kjauth.php in the integrated-with phpBB folder.',
        'PHPBB_INTR_REGENERATE'            => 'Re-generate Api Key',
        'PHPBB_INTR_KJAUTH_FILE'           => 'Create kjauth.php file',
        'PHPBB_INTR_REGENERATE_NOTE'       => 'Are you sure? regenerating the key will disable current integration and will require copying the key to kjauth.php again!',
        'PHPBB_INTR_TEST'                  => 'Test Integration',
        'PHPBB_INTR_TEST_NOTE'             => 'In order to enable integration, test the integration first.',
        'PHPBB_INTR_TEST_NOTE_ERR'         => 'Connection failed! Check settings and test again!',
        'PHPBB_INTR_TEST_NOTE_SUCCESS'     => 'Connection succeeded! You can enable integration now if you want.',
    ],
        'en',
        $plg_id);
};


//plugin update function, called if plugin is already installed but version is different than current
$kleeja_plugin['phpbb_integration']['update'] = function ($old_version, $new_version) {
    // if(version_compare($old_version, '0.5', '<')){
    // 	//... update to 0.5
    // }
    //
    // if(version_compare($old_version, '0.6', '<')){
    // 	//... update to 0.6
    // }

    //you could use update_config, update_olang
};


// plugin uninstalling, function to be called at uninstalling
$kleeja_plugin['phpbb_integration']['uninstall'] = function ($plg_id) {
    //delete options
    delete_config([
        'phpbb_intr_enabled',
        'phpbb_intr_path',
        'phpbb_intr_link',
        'phpbb_intr_api_key',
    ]);


    //delete language variables
    foreach (['ar', 'en'] as $language)
    {
        delete_olang(null, $language, $plg_id);
    }
};


// plugin functions
$kleeja_plugin['phpbb_integration']['functions'] = [
    //add to admin menu
    'begin_admin_page' => function ($args) {
        $adm_extensions = $args['adm_extensions'];
        $ext_icons = $args['ext_icons'];

        $adm_extensions[] = 'phpbb_settings';
        $ext_icons['phpbb_settings'] = 'users';
        return compact('adm_extensions', 'ext_icons');
    },
    //add as admin page to reach when click on admin menu item we added.
    'not_exists_phpbb_settings' => function() {
        $include_alternative = dirname(__FILE__) . '/phpbb_settings.php';

        return compact('include_alternative');
    },
    'data_func_usr_class' => function ($args) {
        global $config;

        if (defined('DISABLE_INTR') || $config['phpbb_intr_enabled'] != 1)
        {
            return;
        }

        $return_now = true;

        $login_status = phpbb_auth_login($args['name'], $args['pass'], $args['expire'], $args['hashed'], $args['loginadm']);

        return compact('return_now', 'login_status');
    },

    'auth_func_usr_class' => function ($args) {
        global $config;

        if (defined('DISABLE_INTR') || $config['phpbb_intr_enabled'] != 1)
        {
            return;
        }

        $return_now = true;

        $auth_status = phpbb_auth_username($args['user_id']);

        return compact('return_now', 'auth_status');
    },
    'login_before_submit' => function($args) {
        if ($args['config']['phpbb_intr_enabled'] == 1) 
        {
            $args['forget_pass_link'] = rtrim($args['config']['phpbb_intr_link'], '/') . '/ucp.php?mode=sendpassword';
            return $args;
        }
    },
    'get_pass_resetpass_link' => function($args) {
        if ($args['config']['phpbb_intr_enabled'] == 1) 
        {
            $args['forgetpass_link'] = rtrim($args['config']['phpbb_intr_link'], '/').'/ucp.php?mode=sendpassword';
            return $args;
        }
    },
    'register_not_default_sys' => function($args) {
        if ($args['config']['phpbb_intr_enabled'] == 1) 
        {
            $args['goto_forum_link'] = rtrim($args['config']['phpbb_intr_link'], '/').'/ucp.php?mode=register';
            return $args;
        }
    },
    'no_submit_profile' => function($args) {
        if ($args['config']['phpbb_intr_enabled'] == 1) 
        {
            $args['goto_forum_link'] = rtrim($args['config']['phpbb_intr_link'], '/').'/ucp.php';
            return $args;
        }
    },
    'end_common' => function($args) {
        if ($args['config']['phpbb_intr_enabled'] == 1) 
        {
            $args['config']['user_system'] = 'phpbb';
            return $args;
        }
    },
];

//includes integration functions
include_once __DIR__ . '/phpbb.php';
