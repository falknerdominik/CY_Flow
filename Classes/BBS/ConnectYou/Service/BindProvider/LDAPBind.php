<?php
namespace BBS\ConnectYou\Service\BindProvider;

/*                                                                        *
 * This script belongs to the Flow package "TYPO3.LDAP".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Bind to an OpenLDAP Server
 *
 * @Flow\Scope("prototype")
 */
class LDAPBind extends AbstractBindProvider {

	/**
	 * Bind to an ldap server in three different ways.
	 *
	 * Settings example for anonymous binding (dn and password will be ignored):
	 *   ...
	 *   bind:
	 *	   anonymous: TRUE
	 *   filter:
	 *	   account: '(uid=?)'
	 *
	 * Settings example for binding with rootDN and admin password:
	 *   ...
	 *   bind:
	 *	   dn: 'uid=admin,dc=example,dc=com'
	 *	   password: 'secret'
	 *   filter:
	 *	   account: '(uid=?)'
	 *
	 * Settings example for binding with userid and password:
	 *   ...
	 *   bind:
	 *	   dn: 'uid=?,ou=Users,dc=example,dc=com'
	 *   filter:
	 *	   account: '(uid=?)'
	 *
	 * @param string $username
	 * @param string $password
	 * @throws \TYPO3\Flow\Error\Exception
	 */
	public function bind($username, $password) {

        error_log("test8");
        $bind = ldap_bind($this->getLinkIdentifier(), $username . "@bbsrb.local", $password);
        if(!$bind){
            echo "Bind ist Fehlgeschlagen!";
        }

/*
		try {
			$anonymousBind = FALSE;
			if (isset($this->options['bind']['anonymous']) && $this->options['bind']['anonymous'] === TRUE) {
				$ldapBindResult = ldap_bind($this->linkIdentifier);
				$anonymousBind = TRUE;
			}

			if (!$anonymousBind) {
				if (empty($this->options['bind']['password'])) {
					$ldapBindResult = ldap_bind($this->linkIdentifier, str_replace('?', $username, $this->options['bind']['dn']), $password);
				} else {
					$ldapBindResult = ldap_bind($this->linkIdentifier, $this->options['bind']['dn'], $this->options['bind']['password']);
				}
			}

			if ($ldapBindResult === FALSE) {
				throw new \TYPO3\Flow\Error\Exception('Could not bind to LDAP server', 1327748989);
			}
		} catch (\Exception $exception) {
			throw new \TYPO3\Flow\Error\Exception('Could not bind to LDAP server', 1327748989);
		}*/
	}

	/**
	 * Bind by $username and $password
	 *
	 * @param string $username
	 * @param string $password
	 * @throws \TYPO3\Flow\Error\Exception
	 */
	public function verifyCredentials($username, $password) {
		try {
			$ldapBindResult = ldap_bind($this->linkIdentifier, $username, $password);
			if ($ldapBindResult === FALSE) {
				throw new \TYPO3\Flow\Error\Exception('Could not verify credentials for dn: "' . $username . '"', 1327749076);
			}
		} catch (\Exception $exception) {
			throw new \TYPO3\Flow\Error\Exception('Could not verify credentials for dn: "' . $username . '"', 1327749076);
		}
	}

}

?>