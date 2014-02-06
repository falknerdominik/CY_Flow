<?php
namespace BBS\ConnectYou\Service\BindProvider;

use TYPO3\Flow\Annotations as Flow;

/**
 * Bindet zu einem LDAP Server
 *
 * @Flow\Scope("prototype")
 */
abstract class AbstractBindProvider implements \BBS\ConnectYou\Service\BindProvider\BindProviderInterface {
	/**
	 * @var resource
	 */
	protected $linkIdentifier;

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @param resource $linkIdentifier
	 * @param array $options
	 */
	public function __construct($linkIdentifier, array $options) {
		$this->linkIdentifier = $linkIdentifier;
		$this->options = $options;
	}

	/**
	 * @return resource
	 */
	public function getLinkIdentifier() {
		return $this->linkIdentifier;
	}

	/**
	 * @param string $username
	 * @return string
	 */
	public function getFilteredUsername($username) {
		return $username;
	}

}

?>