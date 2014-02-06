<?php
namespace BBS\ConnectYou\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class AdminController extends \TYPO3\Flow\Mvc\Controller\ActionController {

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\ProjectRepository
     */
    protected $projectRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\Context
     */
    protected $securityContext;

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\ClientRepository
     */
    protected $clientRepository;

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\TeacherRepository
     */
    protected $teacherRepository;

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\StudentRepository
     */
    protected $studentRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
        // Für jede View - Anzeigen des Benutzernamens .. Link zur Pinnwand
        $this->view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());

        // finde das zugewiesene Projekt wenn vorhanden
        $userproject = $this->findUserProject();
        if($userproject){
            $this->view->assign('userproject', $userproject);
        }

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
                if(count($project->getTeam(), COUNT_RECURSIVE) > 1){ // Prüft ob die ArrayCollection mehr als 1 Objekt enthält (obj->isEmpty() funktioniert nicht
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