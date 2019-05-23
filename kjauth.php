<?php


define('IN_PHPBB', true);
$phpbb_root_path = defined('PHPBB_ROOT_PATH') ? PHPBB_ROOT_PATH : './';
$phpEx           = substr(strrchr(__FILE__, '.'), 1);
include $phpbb_root_path . 'common.' . $phpEx;
$content_separator = '%|%';


//API_KEY, COPY THIS FROM THE PLUGIN SETTINGS
$script_api_key = '{___API_KEY___}';

//
// dont change after this line !
//

//api key errors
if (base64_decode($request->variable('api_key', '', true)) != $script_api_key)
{
    exit('9000');
}

if ($request->is_set('test'))
{
    exit('9999');
}

if (! $request->is_set('userid') && ! $request->is_set('username'))
{
    exit('8000');
}


//hashed ?
$hashed = $request->is_set('userid');

$c = [
    'pass'     => base64_decode($request->variable('pass', '', true)),
    'username' => urldecode($request->variable('username', '', true)),
    'userid'   => $request->variable('userid', 0, true),
];


$sql = 'SELECT user_id, username, user_password, user_email, user_type
    FROM ' . USERS_TABLE . '
    WHERE ';

if ($hashed)
{
    $sql .= 'user_id = ' . $c['userid'] . " AND user_password = '" . $db->sql_escape($c['pass']) . "'";
}
elseif (isset($_GET['return_username']))
{
    $sql .= 'user_id = ' . $c['userid'];
}
else
{
    $sql .= "username_clean = '" . $db->sql_escape(utf8_clean_string($c['username'])) . "'";
}

$result = $db->sql_query($sql);
$row    = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

if (! $row)
{
    exit('7000');
}

//check for password
if (! $hashed)
{
    $passwords_manager = $phpbb_container->get('passwords.manager');

    if (! $passwords_manager->check($c['pass'], $row['user_password']))
    {
        exit('6000');
    }
}

//return only username
if ($request->is_set('return_username'))
{
    exit(base64_encode('1' . $content_separator . $row['username']));
}

$is_admin_or_no = $row['user_type'] == 3 ? '1' : '0';

exit(
    base64_encode('1' . $content_separator . $row['user_id'] . $content_separator . $row['username'] . $content_separator . $row['user_email'] . $content_separator . $row['user_password'] . $content_separator . $is_admin_or_no)
);
