<?php
namespace BBS\ConnectYou\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use Doctrine\Common\Util\Debug;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use BBS\ConnectYou\Domain\Model\Project;
use TYPO3\Flow\Error\Debugger;

class MarketplaceController extends ActionController {

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
		$this->view->assign('projects', $this->projectRepository->findAll());
        #echo $project->getTeam()->count();

        // Für jede View - Anzeigen des Benutzernamens .. Link zur Pinnwand
        $this->view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());

        // finde das zugewiesene Projekt wenn vorhanden
        $userproject = $this->findUserProject();
        if($userproject){
            $this->view->assign('userproject', $userproject);
        }
        #echo $userproject->getTeam();
    }

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function showAction(\BBS\ConnectYou\Domain\Model\Project $project) {
        $this->view->assign('project', $project);
        $this->view->assign('clients', $this->clientRepository->findAll());

        // Für jede View - Anzeigen des Benutzernamens .. Link zur Pinnwand
        $this->view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());

        // finde das zugewiesene Projekt wenn vorhanden
        $userproject = $this->findUserProject();
        if($userproject){
            $this->view->assign('userproject', $userproject);
        }
	}

	/**
     * Zeigt das Erstellformular an
     *
	 * @return void
	 */
	public function newAction() { // zeigt die Erstell Form an
        // findet den zugehörigen Auftraggeber und leitet an die view weiter
        $this->view->assign('client', $this->securityContext->getAccount()->getParty());

        // Für jede View - Anzeigen des Benutzernamens .. Link zur Pinnwand
        $this->view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());

        // finde das zugewiesene Projekt wenn vorhanden
        $userproject = $this->findUserProject();
        if($userproject){
            $this->view->assign('userproject', $userproject);
        }
	}

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $newProject
	 * @return void
	 */
	public function createAction(\BBS\ConnectYou\Domain\Model\Project $newProject) {
        // Aktuelles Datum
        $curYear = date('Y');
        $nextYear = date('Y') + 1;
        $newProject->setYear($curYear . "-" . $nextYear);

	    $this->projectRepository->add($newProject);
		$this->addFlashMessage('Das Projekt "' . $newProject->getName() . '" ');
		$this->redirect('index');
	}

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function editAction(\BBS\ConnectYou\Domain\Model\Project $project) {
		$this->view->assign('project', $project);

        $this->view->assign('clients', $this->clientRepository->findAll());
        $this->view->assign('students', $this->studentRepository->findAll());
        $this->view->assign('teachers', $this->teacherRepository->findAll());

        // Für jede View - Anzeigen des Benutzernamens .. Link zur Pinnwand
        $this->view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());

        // finde das zugewiesene Projekt wenn vorhanden
        $userproject = $this->findUserProject();
        if($userproject){
            $this->view->assign('userproject', $userproject);
        }
	}

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function updateAction(\BBS\ConnectYou\Domain\Model\Project $project) {

        // TODO: unsauber
        if(count($project->getTeam(), COUNT_RECURSIVE) == 1)
            $project->setTeam();

		$this->projectRepository->update($project);
		$this->addFlashMessage('Updated the project.');
		$this->redirect('index');
	}

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function deleteAction(\BBS\ConnectYou\Domain\Model\Project $project) {
		$this->projectRepository->remove($project);
		$this->addFlashMessage('Deleted a project.');
		$this->redirect('index');
	}

    /*
     * Eigene Funktionen
     */

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