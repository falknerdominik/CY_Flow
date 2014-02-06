<?php
namespace BBS\ConnectYou\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class AccountController extends \TYPO3\Flow\Mvc\Controller\ActionController {

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
     * @var \BBS\ConnectYou\Domain\Repository\StudentRepository
     */
    protected $studentRepository;

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\TeacherRepository
     */
    protected $teacherRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
        // Der Benutzername für den Logout Button
        $this->view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());

        // assign Party
        $this->view->assign('party', $this->securityContext->getParty());

        // finde das zugewiesene Projekt wenn vorhanden
        $userproject = $this->findUserProject();
        if($userproject){
            $this->view->assign('userproject', $userproject);
        }
	}

    /**
     * Updatet Auftraggeber
     *
     * @param string $companyName
     * @param string $plz
     * @param string $city
     * @param string $street
     * @param string $streetNumber
     * @param string $telephone
     * @param string $email
     */
    public function updateclientAction($companyName, $plz, $city, $street, $streetNumber, $telephone, $email){
        $client = $this->securityContext->getParty();

        // Setzt den Client
        $client->setName($companyName);
        $client->setPlz($plz);
        $client->setCity($city);
        $client->setStreet($street);
        $client->setStreetnumber($streetNumber);
        $client->setEmail($email);
        $client->setTelephone($telephone);


        $this->clientRepository->update($client);
        $this->addFlashMessage('Ihr Benutzer wurde geupdatet');
        $this->redirect('index');

    }

    /**
     * Updatet Student
     *
     * @param string $fname
     * @param string $lname
     * @param string $email
     * @param string $class
     */
    public function updatestudentAction($fname, $lname, $email, $class){
        $student = $this->securityContext->getParty();

        // Setzt den Client
        $student->setFname($fname);
        $student->setLname($lname);
        $student->setClass($class);
        $student->setEmail($email);

        $this->studentRepository->update($student);
        $this->addFlashMessage('Ihr Benutzer wurde geupdatet');
        $this->redirect('index');
    }

    /**
     * Updatet Teacher
     *
     * @param string $fname
     * @param string $lname
     * @param string $email
     */
    public function updateteacherAction($fname, $lname, $email){
        $teacher = $this->securityContext->getParty();

        // Setzt den Client
        $teacher->setFname($fname);
        $teacher->setLname($lname);
        $teacher->setEmail($email);

        $this->teacherRepository->update($teacher);
        $this->addFlashMessage('Ihr Benutzer wurde geupdatet');
        $this->redirect('index');
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