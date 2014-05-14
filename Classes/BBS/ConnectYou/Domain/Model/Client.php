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
class Client extends \TYPO3\Party\Domain\Model\AbstractParty {

	/**
	 * @var string
	 */
	protected $name;

    /**
     * @var string
     */
    protected $plz;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $streetNumber;

    /**
     * @var string
     */
    protected $telephone;

	/**
	 * @var string
	 */
	protected $email;

    /**
     * @var \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Project>
     * @ORM\OneToMany(mappedBy="client")
     */
    protected $project;

    /**
     * Setzt Projekt
     *
     * @param \BBS\ConnectYou\Domain\Model\Project $project
     * @return void
     */
    public function setProject($project){
        $this->project = $project;
    }

    /**
     * Gibt das Projekt
     * 
     * @return \BBS\ConnectYou\Domain\Model\Project
     */
    public function getProject(){
        return $this->project;
    }

    /**
     *
     */
    public function clearProjects(){
        $this->project = array();
    }


    /**
	 * Setzt den Namen der Person
	 *
	 * @param string $name Name of this person
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the current name of this person
	 *
	 * @return string Name of this person
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * Sets (and adds if necessary) the primary electronic address of this person.
	 *
	 * @param string $electronicAddress Email
	 * @return void
	 */
	public function setEmail($electronicAddress) {
		$this->email = $electronicAddress;
	}

	/**
	 * Returns the primary electronic address, if one has been defined.
	 *
	 * @return string The primary electronic address or NULL
	 */
	public function getEmail() {
		return $this->email;
	}

    /**
     * @return string
     */
    public function getPlz() {
        return $this->plz;
    }

    /**
     * @param string $plz
     * @return void
     */
    public function setPlz($plz) {
        $this->plz = $plz;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $city
     * @return void
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * @param string $street
     * @return void
     */
    public function setStreet($street) {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getStreetNumber() {
        return $this->streetNumber;
    }

    /**
     * @param string $streetNumber
     * @return void
     */
    public function setStreetNumber($streetNumber) {
        $this->streetNumber = $streetNumber;
    }

    /**
     * @return string
     */
    public function getTelephone() {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     * @return void
     */
    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }
}

?>