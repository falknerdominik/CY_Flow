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
     * @param BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function indexAction(\BBS\ConnectYou\Domain\Model\Project $project = NULL) { // Zeigt die Pinnwand an (inkl. Widgets)

        // Assign das Projekt selber
        if($project)
            $this->view->assign('project', $project);
        else
            $this->view->assign('project', $this->findUserProject());

        // Prüft ob er Ein Student ist
        if($this->securityContext->getParty()->getName())
            $this->view->assign('isStudent', "TRUE");

        // Der Benutzername für den Logout Button
        $this->view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());
	}



    /**
     * @param BBS\ConnectYou\Domain\Model\Project $project
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
        // Zuerst der Party
        $account = $this->securityContext->getAccount()->getParty();

        // Alle Projekte Laden
        $projects = $this->projectRepository->findAll();

        // Initalisiert die Variable für spätere nutzung
        $projectDesUsers = NULL;

        if($projects){ // Prüft ob Projekte vorhanden sind
            // Wenn der User ein Client ist
            foreach ($projects as $project) {// Gehe durch alle Projekte
                if($project->getClient() && $project->getClient()->getName() == $account->getName()) // Client != NULL und der Name des Clienten mit dem eingeloggten User Überinstimmt
                    $projectDesUsers = $project; // Das Projekt in dem der User teilnimmt
            }

            // Wenn der User ein Student ist
            foreach ($projects as $project) { // Gehe durch alle Projekte
                if($project->getTeam()){
                    foreach($project->getTeam() as $teammember){ // Gehe durch alle Teammitglieder
                        if($teammember->getName() == $account->getName())
                            $projectDesUsers = $project; // Das Projekt in dem der User teilnimmt
                    }
                }
            }
        }

        // Returned das Projekt ... Wenn Gefunden Sonst NULL
        return $projectDesUsers;
    }

}

?>