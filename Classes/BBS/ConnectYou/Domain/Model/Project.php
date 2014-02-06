<?php
namespace BBS\ConnectYou\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use Doctrine\ORM\Query;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Project {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
     * @Flow\Validate(type="Text")
     * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=1000 })
     * @ORM\Column(length=1000)
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Assignments>
     * @ORM\Column(nullable=true)
	 */
	protected $assignments;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Student>
     * @ORM\Column(nullable=true)
	 */
	protected $team;

	/**
	 * @var \BBS\ConnectYou\Domain\Model\Client
     * @ORM\Column(nullable=true)
     * @ORM\ManyToOne
	 */
	protected $client;

	/**
	 * @var \BBS\ConnectYou\Domain\Model\Widgets
     * @ORM\OneToOne
     * @ORM\Column(nullable=true)
	 */
	protected $widgetsPublic;

	/**
	 * @var \BBS\ConnectYou\Domain\Model\Widgets
     * @ORM\OneToOne
     * @ORM\Column(nullable=true)
	 */
	protected $widgetsPrivate;

    /**
     * @var boolean
     */
    protected $archived;

    /**
     * @var string
     */
    protected $year;

    /**
     * @var \BBS\ConnectYou\Domain\Model\Teacher
     * @ORM\ManyToOne
     * @ORM\Column(nullable=true)
     */
    protected $caretaker;

    /**
     * @return \BBS\ConnectYou\Domain\Model\Teacher
     */
    public function getCaretaker(){
        return $this->caretaker;
    }

    /**
     * @param \BBS\ConnectYou\Domain\Model\Teacher $caretaker
     */
    public function setCaretaker($caretaker){
        $this->caretaker = $caretaker;
    }

    /**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Assignments>
	 */
	public function getAssignments() {
		return $this->assignments;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Assignments> $assignments
	 * @return void
	 */
	public function setAssignments(\Doctrine\Common\Collections\Collection $assignments = NULL) {
            $this->assignments = $assignments;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Students>
	 */
	public function getTeam() {
		return $this->team;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Students> $team
	 * @return void
	 */
	public function setTeam(\Doctrine\Common\Collections\Collection $team = NULL) {
		    $this->team = $team;
	}

	/**
	 * @return \BBS\ConnectYou\Domain\Model\Client
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Client $Client
	 * @return void
	 */
	public function setClient($Client) {
		$this->client = $Client;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Widget>
	 */
	public function getWidgetsPublic() {
		return $this->widgetsPublic;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Widget> $widgetsPublic
	 * @return void
	 */
	public function setWidgetsPublic(\Doctrine\Common\Collections\Collection $widgetsPublic = NULL) {
		$this->widgetsPublic = $widgetsPublic;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Widget>
	 */
	public function getWidgetsPrivate() {
		return $this->widgetsPrivate;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Widget> $widgetsPrivate
	 * @return void
	 */
	public function setWidgetsPrivate(\Doctrine\Common\Collections\Collection $widgetsPrivate = NULL) {
		$this->widgetsPrivate = $widgetsPrivate;
	}

    /**
     * @param bool $archived Archiviert?
     * @return void
     */
    public function setArchived($archived){
        $this->archived = $archived;
    }

    /**
     * @return bool
     */
    public function getArchived(){
        return $this->archived;
    }

    /**
     * @param string $year
     * @return void
     */
    public function setYear($year){
        $this->year = $year;
    }

    /**
     * @return bool
     */
    public function getYear(){
        return $this->year;
    }
}
?>