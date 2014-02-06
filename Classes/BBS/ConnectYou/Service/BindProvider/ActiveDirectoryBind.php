<?php
namespace BBS\ConnectYou\Service\BindProvider;

use TYPO3\Flow\Annotations as Flow;

/**
 * Bind zu einem Active Directory Server
 *
 * @Flow\Scope("prototype")
 */
class ActiveDirectoryBind extends AbstractBindProvider {

	/**
	 * Bind to an ActiveDirectory server
	 *
	 * Prefix the username with a domain if configured.
	 *
	 * @param string $username
	 * @param string $password
	 * @throws \TYPO3\Flow\Error\Exception
	 */
	public function bind($username, $password) {
		try {
			if (!empty($this->options['domain'])) {
				if (!strpos($username, '\\')) {
					$username = $this->options['domain'] . '\\' . $username;
				}
			}
			ldap_bind($this->linkIdentifier, $username, $password);
		} catch (\Exception $exception) {
			throw new \TYPO3\Flow\Error\Exception('Could not bind to ActiveDirectory server', 1327937215);
		}
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @throws \TYPO3\Flow\Error\Exception
	 */
	public function verifyCredentials($username, $password) {
		try {
			ldap_bind($this->linkIdentifier, $username, $password);
		} catch (\Exception $exception) {
			throw new \TYPO3\Flow\Error\Exception('Could not verify credentials for dn: "' . $username . '"', 1327763970);
		}
	}

	/**
	 * Return username in format used for directory search
	 *
	 * @param string $username
	 * @return string
	 */
	public function getFilteredUsername($username) {
		if (!empty($this->options['domain'])) {
			$usernameParts = explode('\\', $username);
			$usernameWithoutDomain = array_pop($usernameParts);
			return $this->options['filter']['ignoreDomain'] ? $usernameWithoutDomain : addcslashes($username, '\\');
		}
		return $username;
	}
}

?>