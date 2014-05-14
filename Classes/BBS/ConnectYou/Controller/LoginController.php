<?php
namespace BBS\ConnectYou\Controller;

// Damit die @Annotations funktionieren
use TYPO3\Flow\Annotations as Flow;
use Doctrine\Common\Util\Debug;
use TYPO3\Flow\Mvc\Controller\ActionController;
use BBS\ConnectYou\Domain\Model\Client;
use TYPO3\Flow\Error\Debugger;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */


class LoginController extends ActionController {

    /**
     * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
     * @Flow\Inject
     */
    protected $authenticationManager;

    /**
     * @var \TYPO3\Flow\Security\AccountRepository
     * @Flow\Inject
     */
    protected $accountRepository;

    /**
     * @var \TYPO3\Flow\Security\AccountFactory
     * @Flow\Inject
     */
    protected $accountFactory;

    /**
     * @var \BBS\ConnectYou\Domain\Repository\ClientRepository
     * @Flow\Inject
     */
    protected $clientRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
        // Nur das Loginformular wird Aufgerufen
	}

    /**
     * @return void
     */
    public function registerAction() {
        // zeigt die LoginForm an

    }

    /**
     * Logged den Benutzer ein
     *
     * @return void
     */
    public function authenticateAction() {
        try {
            $this->authenticationManager->authenticate();
            $this->addFlashMessage('Erfolgreich angemeldet.');
            $this->redirect('index', 'Marketplace');
        } catch (\TYPO3\Flow\Exception $exception) {
            // Prüft ob eine Fehlernachricht vorhanden ist. Wenn ja zeigt diese an.
            if($exception->getMessage()){
                $this->addFlashMessage('Falscher Benutzername oder Passwort!'/* . $exception->getMessage()*/);
                //$this->addFlashMessage($exception->getMessage());
                $this->redirect('index', 'Login');
            }
        }
    }

    /**
     * Erstellt den Benutzer
     *
     * @param string $name
     * @param string $pass
     * @param string $pass2
     * @param string $companyName
     * @param string $plz
     * @param string $city
     * @param string $street
     * @param string $streetNumber
     * @param string $telephone
     * @param string $email
     */
    public function createAction($name, $pass, $pass2, $companyName, $plz, $city, $street, $streetNumber, $telephone, $email) {

        $defaultRole = 'BBS.ConnectYou:Client';

        if($name == '' || strlen($name) < 5) {
            $this->addFlashMessage('Benutzername muss mindestens 5 Zeichen lang sein.');
            $this->redirect('register', 'Login');

        // Prüft ob das Passwort leer ist || Ob beide Passworteingaben übereinstimmen || Ob das Passwort mindestens 6 Zeichen lang ist
        } else if($pass == '' || $pass != $pass2) {
            $this->addFlashMessage('Passwörter sind zu kurz oder stimmen nicht überein.');
            $this->redirect('register', 'Login');
        } else {

            // Erstellt einen Account mit Passwort
            $account = $this->accountFactory->createAccountWithPassword($name, $pass, array($defaultRole));
            $this->accountRepository->add($account);

            //Erstellen des Auftraggebers
            $newClient = new \BBS\ConnectYou\Domain\Model\Client();

            // Weise die Eigenschaften dem Auftraggeber zu
            $newClient->setName($companyName);
            $newClient->setPlz($plz);
            $newClient->setCity($city);
            $newClient->setStreet($street);
            $newClient->setStreetNumber($streetNumber);
            $newClient->setTelephone($telephone);
            $newClient->setEmail($email);

            // Füge Account hinzu
            $newClient->addAccount($account);

            // Speichere Auftraggeber
            $this->clientRepository->add($newClient);
            echo "test";

            // Weiterleitung zum Login
            $this->addFlashMessage('Ihr Account wurde erstellt. Bitte melden sie sich an.');
            $this->redirect('index');
        }

        // redirect to the login form
        $this->redirect('index', 'Login');
    }

    /**
     * Meldet den Benutzer ab
     *
     * @return void
     */
    public function logoutAction() {
        $this->authenticationManager->logout();
        $this->addFlashMessage('Erfolgreich abgemeldet.');
        $this->redirect('index', 'Login');
    }
}

?>
