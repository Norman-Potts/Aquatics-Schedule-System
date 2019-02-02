<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
	Provide the asset url path when requested.
*/
if (!function_exists('assetUrl'))
{
    function assetUrl()
    {
        $CI =& get_instance();
        return base_url() . $CI->config->item('assetsPath');
    }
}

?>