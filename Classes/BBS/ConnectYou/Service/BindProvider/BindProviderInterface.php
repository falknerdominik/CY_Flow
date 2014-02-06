<?php
namespace BBS\ConnectYou\Service\BindProvider;

/**
 * Interface für Server binding
 */
interface BindProviderInterface {

	/**
	 * Der link identifier
	 *
	 * @return resource
	 */
	public function getLinkIdentifier();

	/**
	 * Bind to Server
	 *
	 * @param $username
	 * @param $password
	 */
	public function bind($username, $password);

	/**
	 * Binde DN und Passwort
	 *
	 * @param $dn
	 * @param $password
	 */
	public function verifyCredentials($dn, $password);

	/**
	 * Get den FU
	 *
	 * @param $username
	 */
	public function getFilteredUsername($username);
}

?>