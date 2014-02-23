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
     * Diese Funktion wird immer aufgerufen wenn eine View dieses Controllers aufgerufen wird, d.h. wird sie dazu
     * verwenden 'globale' Variablen für die Views festzusetzen (z.b.: Nutzername für etc wird den Views immer übergeben)
     *
     * @return void
     */
    protected function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view){
        // finde das zugewiesene Projekt wenn vorhanden und der Nutzer kein Lehrer ist
        if(!in_array("BBS.ConnectYou:Teacher", $this->securityContext->getAccount()->getRoles())){
            $view->assign('userproject', $this->findUserProject());
        }

        // Benutzername für Benutzermenü
        $view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());
    }

	/**
	 * @return void
	 */
	public function indexAction() {
        // assign Party
        $this->view->assign('party', $this->securityContext->getParty());
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
     * Finde ob User Teil eines Projektes ist (Wenn ja wird ein Link zu einer Pinnwand gerendert)
     */
    protected function findUserProject(){
        // Zuerst der Party
        $party = $this->securityContext->getAccount()->getParty();

        // Initalisiert die Variable für spätere nutzung
        $projectDesUsers = $party->getProject();

        // Returned das Projekt ... Wenn Gefunden Sonst NULL
        return $projectDesUsers;
    }

}

?>