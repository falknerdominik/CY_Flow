<?php
namespace BBS\ConnectYou\Utility;

use TYPO3\Flow\Annotations as Flow;

/**
 * Ein Tool zur Serverkontrolle
 *
 * @Flow\Scope("prototype")
 */
class ServerStatusUtility {

	/**
	 * Checkt ob der Server Online ist
	 *
	 * @return boolean
	 */
	public static function isServerOnline($host, $port) {
        try{
            $fp = fsockopen(str_replace("ldap://", "", $host), $port, $errno, $erro, 5);
            return TRUE;
        } catch(\Exception $e){
	    return FALSE;
        }
	}
}

?>
