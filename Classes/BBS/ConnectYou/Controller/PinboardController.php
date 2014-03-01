<?php
namespace BBS\ConnectYou\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use BBS\ConnectYou\Model as CY;

class PinboardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\Context
     */
    protected $securityContext;

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\AssignmentRepository
     */
    protected $assignmentRepository;

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\ProjectRepository
     */
    protected $projectRepository;

    /**
     * Diese Funktion wird immer aufgerufen wenn eine View dieses Controllers aufgerufen wird, d.h. wird sie dazu
     * verwenden 'globale' Variablen für die Views festzusetzen (z.b.: Nutzername für etc wird den Views immer übergeben)
     *
     * @return void
     */
    protected function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view){
        // finde das zugewiesene Projekt wenn vorhanden und der Nutzer kein Lehrer ist
        if(!in_array("BBS.ConnectYou:Teacher", $this->securityContext->getAccount()->getRoles()) || !in_array("BBS.ConnectYou:Client", $this->securityContext->getAccount()->getRoles())){
            $view->assign('userproject', $this->findUserProject());
        }

        // Benutzername für Benutzermenü
        $view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());
    }

	/**
     * @param BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function indexAction(\BBS\ConnectYou\Domain\Model\Project $project = NULL) { // Zeigt die Pinnwand an (inkl. Widgets)
        // Assign das Projekt selber
        if($project)
            $this->view->assign('project', $project);
        else
            $this->view->assign('project', $this->findUserProject());
	}



    /**
     * @param \BBS\ConnectYou\Domain\Model\Project $project
     * @return void
     */
    public function assignmentsAction(\BBS\ConnectYou\Domain\Model\Project $project){ // Finde die Assignments des Angemeldeten users
        // Der User wird zur view geworfen
        $this->view->assign('user', $this->securityContext->getParty());
        #$this->assignmentRepository->findByStudent($this->securityContext->getParty());
        #$this->view->assing('assignments', $this->assignmentRepository->findByStudent($this->securityContext->getParty()));

    }

    /**
     * Finde ob User Teil eines Projektes ist (Wenn ja wird ein Link zu einer Pinnwand gerendert)
     */
    protected function findUserProject(){
        $projectDesUsers = NULL;
        // Zuerst der Party
        $party = $this->securityContext->getAccount()->getParty();

        // Initalisiert die Variable für spätere nutzung
        if(!in_array("BBS.ConnectYou:Teacher", $this->securityContext->getAccount()->getRoles())){
            $projectDesUsers = $party->getProject();
        }


        // Returned das Projekt ... Wenn Gefunden Sonst NULL
        return $projectDesUsers;
    }

}

?>