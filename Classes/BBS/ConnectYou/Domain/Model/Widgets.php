<?php
namespace BBS\ConnectYou\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use Doctrine\Common\Collections\ArrayCollection;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Widgets {

	/**
	 * @var \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\NotepadWidget>
     * @ORM\Column(nullable=true)
	 */
	protected $notepads;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\TodoWidget>
     * @ORM\Column(nullable=true)
	 */
	protected $todos;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\GalleryWidget>
     * @ORM\Column(nullable=true)
	 */
	protected $gallerys;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\MilestoneWidget>
     * @ORM\Column(nullable=true)
	 */
	protected $milestones;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\DocumentWidget>
     * @ORM\Column(nullable=true)
	 */
	protected $documents;

    public function __construct() {
        $this->notepads = new ArrayCollection();
        $this->gallerys = new ArrayCollection();
        $this->milestones = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->todos = new ArrayCollection();
    }

	/**
	 * @return \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\NotepadWidget>
	 */
	public function getNotepads() {
		return $this->notepads;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\NotepadWidget> $notepads
	 * @return void
	 */
	public function setNotepads(\Doctrine\Common\Collections\Collection $notepads) {
		$this->notepads = $notepads;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\TodoWidget>
	 */
	public function getTodos() {
		return $this->todos;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\TodoWidget> $todos
	 * @return void
	 */
	public function setTodos(\Doctrine\Common\Collections\Collection $todos) {
		$this->todos = $todos;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\GalleryWidget>
	 */
	public function getGallerys() {
		return $this->gallerys;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\GalleryWidget> $gallerys
	 * @return void
	 */
	public function setGallerys(\Doctrine\Common\Collections\Collection $gallerys) {
		$this->gallerys = $gallerys;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\MilestoneWidget>
	 */
	public function getMilestones() {
		return $this->milestones;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\MilestoneWidget> $milestones
	 * @return void
	 */
	public function setMilestones(\Doctrine\Common\Collections\Collection $milestones) {
		$this->milestones = $milestones;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\DocumentWidget>
	 */
	public function getDocuments() {
		return $this->documents;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\DocumentWidget> $documents
	 * @return void
	 */
	public function setDocuments(\Doctrine\Common\Collections\Collection $documents) {
		$this->documents = $documents;
	}

}
?>