<?php
namespace BBS\ConnectYou\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ein Projekt
 *
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
     * @ORM\OneToMany(mappedBy="project", cascade={"persist"})
     * @ORM\Column(nullable=true)
	 */
	protected $team;

	/**
	 * @var \BBS\ConnectYou\Domain\Model\Client
     * @ORM\Column(nullable=true)
     * @ORM\ManyToOne(inversedBy="project")
	 */
	protected $client;

	/*/**
	 * @var \BBS\ConnectYou\Domain\Model\Widgets
     * @ORM\OneToOne
     * @ORM\Column(nullable=true)
	 */
	/*protected $widgetsPublic;*/

	/*/**
	 * @var \BBS\ConnectYou\Domain\Model\Widgets
     * @ORM\OneToOne
     * @ORM\Column(nullable=true)
	 */
	/*protected $widgetsPrivate;*/

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

    public function __construct() {
        $this->team = new ArrayCollection();
        #$this->assignments = new ArrayCollection();
        $this->widgetsPrivate = new ArrayCollection();
        $this->widgetsPublic = new ArrayCollection();
    }

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
	public function setAssignments($assignments) {
            $this->assignments = $assignments;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getTeam() {
		return $this->team;
	}

    /**
     * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Student> $team
     * @return void
     */
    public function setTeam($team) {
        $this->team->clear();
        foreach($team as $t){
            $this->addTeammember($t);
            $t->setProject($this);
        }
    }

    /**
     * @param \BBS\ConnectYou\Domain\Model\Student $student
     * @return void
     */
    public function addTeammember($student){
        $this->team->add($student);
    }

    /**
     * @return void
     */
    public function removeTeam(){
        $this->team->clear();
    }

    /**
     * @param \BBS\ConnectYou\Domain\Model\Student $student
     * @return void
     */
    public function removeTeammember($student){
        $this->team->remove($student);
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
        if($Client){
            $Client->setProject($this);
            $this->client = $Client;
        }
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
        if($widgetsPublic->isEmpty()) // Prüft ob die Collection leer ist wenn ja wird NULL reingeschrieben (und keine leere Collection!)
		    $this->widgetsPublic = NULL;
        else
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
        if($widgetsPrivate->isEmpty()) // Prüft ob die Collection leer ist wenn ja wird NULL reingeschrieben (und keine leere Collection!)
		    $this->widgetsPrivate = NULL;
        else
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