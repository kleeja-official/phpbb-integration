<?php
/**
*
* @package auth
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


//no for directly open
if (! defined('IN_COMMON'))
{
    exit;
}

if (! function_exists('phpbb_auth_test'))
{
    function phpbb_auth_test()
    {
        global $config;

        $api_http_query = 'api_key=' . urlencode(base64_encode($config['phpbb_intr_api_key'])) . '&test=1';

        $phpbbLink = rtrim($config['phpbb_intr_link'], '/') . '/kjauth.php';

        $remote_data = FetchFile::make($phpbbLink . '?' . $api_http_query)
            ->get();

        if (! empty($remote_data) && intval($remote_data) == 9999)
        {
            return true;
        }

        return false;
    }
}

if (! function_exists('phpbb_auth_login'))
{
    function phpbb_auth_login($name, $pass, $hashed = false, $expire, $loginadm = false, $return_username = false)
    {
        global $config, $usrcp, $userinfo;

        $user_data = false;

        if ($usrcp->kleeja_get_cookie('ulogu'))
        {
            list($_, $hashed_password, $__, $___, $group_id, $u_info) =  @explode('|', $usrcp->en_de_crypt($usrcp->kleeja_get_cookie('ulogu'), 2));

            $userinfo = unserialize(base64_decode($u_info));

            if ($userinfo['last_visit'] > time() - 3600)
            {
                $userinfo['group_id'] = $group_id;
                $userinfo['password'] = $hashed_password;
                $user_data            = true;
            }
        }


        if (! $user_data)
        {
            $api_http_query = 'api_key=' . urlencode(base64_encode($config['phpbb_intr_api_key'])) . '&'
            . ($hashed ? 'userid' : 'username') . '=' . urlencode($name) . '&pass=' . urlencode(base64_encode($pass));

            //if only username, let's add it to the query
            $api_http_query .= $return_username ? '&return_username=1' : '';


            //get it
            $phpbbLink = rtrim($config['phpbb_intr_link'], '/') . '/kjauth.php';

            $remote_data = FetchFile::make($phpbbLink . '?' . $api_http_query)
                ->get();

            //no response
            //empty or can not connect
            if (empty($remote_data) || is_numeric($remote_data))
            {
                return false;
            }

            $user_info = explode('%|%', base64_decode($remote_data));

            //1 == success
            if ((int) $user_info[0] !== 1)
            {
                return false;
            }

            $userinfo['id']       = $user_info[1];
            $userinfo['name']     = $user_info[2];
            $userinfo['mail']     = $user_info[3];
            $userinfo['group_id'] = intval($user_info[5]) == 1 ? 1 : 3;
            $userinfo['password'] = $user_info[4];
        }


        //
        //if we want username only we have to return it quickly and die here
        //
        if ($return_username)
        {
            return $userinfo['id'];
        }

        //
        //in case of admin, we just want a check, no data setup ..
        //
        if (! $loginadm)
        {
            define('USER_ID', $userinfo['id']);
            define('USER_NAME', $userinfo['name']);
            define('USER_MAIL', $userinfo['mail']);
            define('GROUP_ID', $userinfo['group_id']);
        }

        //user ifo
        //and this must be filled with user data comming from url
        $userinfo['founder']  = 1;
        $user_y               = base64_encode(serialize(['id'=>$userinfo['id'], 'name'=>$userinfo['name'], 'mail'=>$userinfo['mail'], 'founder'=>1, 'last_visit'=>time()]));


        //add cookies
        if (! $hashed && ! $loginadm)
        {
            $usrcp->kleeja_set_cookie(
                                'ulogu', $usrcp->en_de_crypt($userinfo['id'] . '|' . $userinfo['password'] . '|' . $expire . '|' .
                                sha1(md5($config['h_key'] . $userinfo['password']) . $expire) . '|' . $userinfo['group_id'] . '|' . $user_y), $expire
                            );
        }

        return true;
    }
}


if (! function_exists('phpbb_auth_username'))
{
    function phpbb_auth_username($user_id)
    {
        return phpbb_auth_login($user_id, false, false, 0, false, true);
    }
}
