<?php
/*
 * The Flash uploader has its own useragent and cookie space, which means
 * that we have to tweak the session config in several ways to stop things
 * breaking:
 * (a) we need to make sure the session library doesn't attempt to
 *     validate the useragent.
 * (b) we need to make sure that it doesn't change the session key,
 *     otherwise the browser will end up with a stale key.  
 */

Event::add('system.ready', 'fancyupload_modify_session_config');

function fancyupload_modify_session_config()
{
	if (stripos(Kohana::user_agent(), 'Shockwave Flash') !== FALSE)
	{
		// Check that the session isn't attempting to validate the user agent
		$validate = Kohana::config('session.validate');
		$key = array_search('user_agent', $validate);
		
		if ($key !== FALSE)
		{
			unset($validate[$key]);
			Kohana::config('session.validate', $validate);
		}
		
		// Ensure that the session key won't be regenerated
		Kohana::config_set('session.regenerate', 0);
	}
}

