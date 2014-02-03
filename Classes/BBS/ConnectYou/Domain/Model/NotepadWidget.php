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
class NotepadWidget {

	/**
	 * @var string
	 */
	protected $content;

    /**
     * @var string
     */
    protected $title;

    /**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string $content
	 * @return void
	 */
	public function setContent($content) {
		$this->content = $content;
	}

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle($title) {
        $this->title = $title;
    }

}
?>