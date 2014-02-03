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
class Assignment {

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var \DateTime
	 */
	protected $startTime;

	/**
	 * @var \DateTime
	 */
	protected $endTime;

	/**
	 * @var \BBS\ConnectYou\Domain\Model\Student
     * @ORM\OneToOne
	 */
	protected $student;


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
	 * @return \DateTime
	 */
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * @param \DateTime $startTime
	 * @return void
	 */
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * @param \DateTime $endTime
	 * @return void
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * @return \BBS\ConnectYou\Domain\Model\Student
	 */
	public function getStudent() {
		return $this->student;
	}

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Student $student
	 * @return void
	 */
	public function setStudent($student) {
		$this->student = $student;
	}

}
?>