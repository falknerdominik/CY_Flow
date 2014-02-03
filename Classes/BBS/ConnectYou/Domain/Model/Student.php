<?php
namespace BBS\ConnectYou\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Party".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A person
 *
 * @Flow\Entity
 */
class Student extends \TYPO3\Party\Domain\Model\AbstractParty {

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
	 * Constructs this Person
	 *
	 */
	public function __construct() {
		parent::__construct(); // für AbstractParty den Konstruktor ausführen
	}

	/**
	 * Setzt den Vornamen
	 *
	 * @param string $fname der neue Vorname des Studenten
	 * @return void
	 */
	public function setFname($fname) {
		$this->fname = $fname;
	}

	/**
	 * Gibt den Vornamen zurück
	 *
	 * @return string Vorname des Studenten
	 */
	public function getFname() {
		return $this->fname;
	}

    /**
     * Setzt den Nachnamen
     *
     * @param string $lname Nachname des Studenten
     * @return void
     */
    public function setLname($lname) {
        $this->lname = $lname;
    }

    /**
     * Returns the current name of this person
     *
     * @return string Nachname des Studenten
     */
    public function getLname() {
        return $this->lname;
    }

    /**
     * Gibt den ganzen Namen Zurück (fname . " " . lname => "Dominik Falkner"
     *
     * @return string der ganze Name des Studenten
     */
    public function getName(){
        return $this->fname . " " . $this->lname;
    }

    /**
	 * Returns all known electronic addresses of this person.
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets (and adds if necessary) the primary electronic address of this person.
	 *
	 * @param string The electronic address
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}
}

?>