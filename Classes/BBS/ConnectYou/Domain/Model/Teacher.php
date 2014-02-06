<?php
namespace BBS\ConnectYou\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Teacher extends \TYPO3\Party\Domain\Model\AbstractParty {

	/**
	 * @var string
	 */
	protected $fname;

	/**
	 * @var string
	 */
	protected $lname;

	/**
	 * @var string
	 */
	protected $email;


	/**
	 * @return string
	 */
	public function getFname() {
		return $this->fname;
	}

	/**
	 * @param string $fname
	 * @return void
	 */
	public function setFname($fname) {
		$this->fname = $fname;
	}

	/**
	 * @return string
	 */
	public function getLname() {
		return $this->lname;
	}

	/**
	 * @param string $lname
	 * @return void
	 */
	public function setLname($lname) {
		$this->lname = $lname;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

}
?>