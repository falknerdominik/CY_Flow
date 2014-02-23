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
 * Ein Student
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
    protected $class;

    /**
	 * @var string
	 */
	protected $email;

    /**
     * @var \BBS\ConnectYou\Domain\Model\Project
     * @ORM\ManyToOne(inversedBy="team")
     */
    protected $project;

    /**
     * @var string
     * @FLow\Identity
     */
    protected $bbsid;

    /**
     * Setzt die bbsid
     * @param string $bbsid
     * @return void
     */
    public function setBbsid($bbsid){
        $this->bbsid = $bbsid;
    }

    /**
     * Gibt die bbsid
     * @return string BBSID
     */
    public function getBbsid(){
        return $this->bbsid;
    }

    /**
     * Setzt das Projekt
     *
     * @param \BBS\ConnectYou\Domain\Model\Project Das Projekt zum setzen
     * @return void
     */
    public function setProject($project){
        $this->project = $project;
    }

    /**
     * Gibt das Projekt zurück
     *
     * @return \BBS\ConnectYou\Domain\Model\Project
     */
    public function getProject(){
        return $this->project;
    }

    /**
     * @param \BBS\ConnectYou\Domain\Model\Student $project
     * @return void
     */
    public function setTeam($project) {
        $this->setProject($project);
        $project->addTeammember($this);
    }

    /**
     * Setzt die Klasse
     *
     * @param string $class
     */
    public function setClass($class){
        $this->class = $class;
    }

    /**
     * Gibt die Klasse Zurück
     *
     * @return string Klasse
     */
    public function getClass(){
        return $this->class;
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
     * Gibt Familienname zurück
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
	 * Gibt die Email zurück
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Setzt die Email
	 *
	 * @param string The electronic address
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

    /**
     * Gibt den Namen mit der Klasse in Klammer zurück für die Select-View in Marketplace/edit
     *
     * @return string
     */
    public function getNameandclass(){
        return $this->getName() . " (" . $this->getClass() . ")";
    }
}

?>