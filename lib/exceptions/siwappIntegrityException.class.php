<?php

/*
 * This file is part of the siwapp package.
 * (c) 2011 Jose de Zarate <jzarate@gmail.com>
 *
 */

/**
 * siwappIntegrityException is thrown when an error occurs that affects
 * siwapp's database integrity.
 *
 * @package		siwapp
 * @subpackage	        exception
 * @author		JoeZ99 <jzarate@gmail.com>
 * @version		SVN: $Id$
 */
class siwappIntegrityException extends sfException
{
	/**
	 * Class constructor.
	 *
	 * @param	string	the error message
	 * @param	int		the error code
	 */
	public function __construct($message = null, $code = 0)
	{
		parent::__construct($message, $code);
	}
}