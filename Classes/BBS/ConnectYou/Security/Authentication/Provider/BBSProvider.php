<?php
namespace BBS\ConnectYou\Security\Authentication\Provider;

use TYPO3\Flow\Annotations as Flow;

use TYPO3\Flow\Security\Authentication\TokenInterface;

/**
 * LDAP Authentication provider
 *
 * @Flow\Scope("prototype")
 */
class BBSProvider extends \TYPO3\Flow\Security\Authentication\Provider\PersistedUsernamePasswordProvider {

	/**
	 * @var \BBS\ConnectYou\Service\DirectoryService
	 */
	protected $directoryService;

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
     * @var \TYPO3\Flow\Security\Policy\PolicyService
     * @Flow\Inject
     */
    protected $policyService;

    /**
     * @var \TYPO3\Flow\Security\AccountFactory
     * @Flow\Inject
     */
    protected $accountFactory;

	/**
	 * Constructor
	 *
	 * @param string $name The name of this authentication provider
	 * @param array $options Additional configuration options
	 * @return void
	 */
	public function __construct($name, array $options) {
		$this->name = $name;
		$this->directoryService = new \BBS\ConnectYou\Service\DirectoryService($name, $options);
	}

	/**
     * Authentifiziert mit den derzeitigen Token. Wenn der Server nicht erreichbar ist versucht es
     * gecacheden Anmeldedaten zu verwenden vom letzten erfolgreichen Login.
	 *
	 * @param \TYPO3\Flow\Security\Authentication\TokenInterface $authenticationToken
	 * @return void
	 */
	public function authenticate(TokenInterface $authenticationToken) {
		$account = NULL;
		$credentials = $authenticationToken->getCredentials(); // holt die Credentials heraus (username & passwort)

		if (is_array($credentials) && isset($credentials['username'])) { // prüft ob die Credentials gesetzt sind
			if ($this->directoryService->isServerOnline()) { // Checked ob der Server Online is
				try { // Versucht die Anmeldung, Authentifizierung der User konnte sich am AD Anmelden
					$userarray = $this->directoryService->authenticate($credentials['username'], $credentials['password']);

					if ($userarray) { // Wenn der ldapUser gebunden werden konnte und der Userarray gespeichert wurde
                        // Suche Ob ein Account schon vorhanden ist
						$account = $this->accountRepository->findActiveByAccountIdentifierAndAuthenticationProviderName($credentials['username'], $this->name);

						if (empty($account)) { // Wenn der Account NICHT in der DB vorhanden ist erstelle ihn

                            $defaultRole = NULL;

                            if($userarray['bbsmemberofclass'][0] == "Teachers")
                                $defaultRole = "BBS.ConnectYou:Teacher";
                            else
                                $defaultRole = "BBS.ConnectYou:Student";

                            $account = $this->accountFactory->createAccountWithPassword($credentials['username'], "", array($defaultRole), $this->name);
							$this->accountRepository->add($account); // Fügt den Account hinzu

							$this->createParty($account, $userarray); // Erstellt die Party

						}



						if ($account instanceof \TYPO3\Flow\Security\Account) { // Wenn ein Account vorhanden ist

                            if(in_array("BBS.ConnectYou:Teacher", $account->getRoles()) || $account->getParty()->getBbsid() == $userarray['bbsid'][0]){ // Prüft ob Accont einzigartig ist

                                $account->setCredentialsSource($this->hashService->generateHmac($credentials['password'])); // Passwort Cachen
                                // Account Updaten
                                $this->updateParty($account, $userarray);
                                $authenticationToken->setAuthenticationStatus(TokenInterface::AUTHENTICATION_SUCCESSFUL); // Login Successful
                                $authenticationToken->setAccount($account); // Setzt den Account
                            } else { // Wenn der Account NICHT einzigartig ist (d.h. schon recycled)

                                $this->accountRepository->remove($account); // Löscht alten Account

                                // Neuer Account wird erstellt
                                $defaultRole = NULL;

                                // Prüft welche Rolle der User hat
                                if($userarray['bbsmemberofclass'][0] == "Teachers")
                                    $defaultRole = "BBS.ConnectYou:Teacher";
                                else
                                    $defaultRole = "BBS.ConnectYou:Student";

                                $account = $this->accountFactory->createAccountWithPassword($credentials['username'], "", array($defaultRole), $this->name);

                                $test = $this->accountRepository->findActiveByAccountIdentifierAndAuthenticationProviderName($account->getAccountIdentifier(), $this->name);

                                if(!$test){
                                    $this->accountRepository->add($account); // Fügt den Account hinzu

                                    $this->createParty($account, $userarray); // Erstellt die Party
                                }

                            }
						} elseif ($authenticationToken->getAuthenticationStatus() !== TokenInterface::AUTHENTICATION_SUCCESSFUL) { // Vergleicht ob schon eingeloggt
							$authenticationToken->setAuthenticationStatus(TokenInterface::NO_CREDENTIALS_GIVEN); // sonst NO_CREDENTIALS_GIVEN
						}
					} else {
						$authenticationToken->setAuthenticationStatus(TokenInterface::WRONG_CREDENTIALS); // Letzte Möglichkeit ... falsche Crendentials
					}

				} catch (\Exception $exception) {
                    echo $exception->getMessage() . $exception->getLine();
				}
			} else { // Falls der Server nicht Online ist fall zurück zu den gecached Passworter
				$account = $this->accountRepository->findActiveByAccountIdentifierAndAuthenticationProviderName($credentials['username'], $this->name);

				if ($account instanceof \TYPO3\Flow\Security\Account) { // Wenn ein Account gefunden wurde
					if ($this->hashService->validateHmac($credentials['password'], $account->getCredentialsSource())) {
						$authenticationToken->setAuthenticationStatus(TokenInterface::AUTHENTICATION_SUCCESSFUL); // Einloggen
						$authenticationToken->setAccount($account); // Account Aktualisieren
					} else {
						$authenticationToken->setAuthenticationStatus(TokenInterface::WRONG_CREDENTIALS);
					}
				} elseif ($authenticationToken->getAuthenticationStatus() !== TokenInterface::AUTHENTICATION_SUCCESSFUL) {
					$authenticationToken->setAuthenticationStatus(TokenInterface::NO_CREDENTIALS_GIVEN);
				}
			}
		} else { // Keine Benutzerdaten Gegeben
			// FÜR DEBUGGING
			// $authenticationToken->setAuthenticationStatus(TokenInterface::NO_CREDENTIALS_GIVEN);
		}
	}

	/**
	 * Erstellt den Student oder Leherer Party
	 *
	 * @param \TYPO3\Flow\Security\Account $account Der frisch erstellte Account
	 * @param array $userarray Die Benutzerdaten
	 * @return void
	 */
	protected function createParty(\TYPO3\Flow\Security\Account $account, array $userarray) {
        if($userarray['bbsmemberofclass'][0] == "Teachers"){ // Wenn es sich um einen Lehrer handelt
            $newTeacher = new \BBS\ConnectYou\Domain\Model\Teacher();
            $newTeacher->setFname($userarray['givenname'][0]);
            $newTeacher->setLname($userarray['sn'][0]);
            $newTeacher->setEmail($userarray['mail'][0]);
            $newTeacher->setBbsid(NULL);

            $account->setParty($newTeacher);

            $this->teacherRepository->add($newTeacher);

        } else { // Wenn es ein Schüler ist
            $newStudent = new \BBS\ConnectYou\Domain\Model\Student();
            $newStudent->setFname($userarray['givenname'][0]);
            $newStudent->setLname($userarray['sn'][0]);
            $newStudent->setEmail($userarray['mail'][0]);
            $newStudent->setClass($userarray['bbsmemberofclass'][0]);
            $newStudent->setBbsid($userarray['bbsid'][0]);

            $account->setParty($newStudent);

            $this->studentRepository->add($newStudent);
        }
	}

    /**
     * Updatet die Informationen eines Accounts bei jedem Login
     *
     * @param \TYPO3\Flow\Security\Account $account Der zu updatende Account
     * @param array $userarray Die Benutzerdaten
     * @return void
     */
    protected function updateParty(\TYPO3\Flow\Security\Account $account, array $userarray){
        if($userarray['bbsmemberofclass'][0] == "Teachers"){ // Wenn es sich um einen Lehrer handelt
            $newTeacher = $account->getParty();
            $newTeacher->setFname($userarray['givenname'][0]);
            $newTeacher->setLname($userarray['sn'][0]);
            $newTeacher->setEmail($userarray['mail'][0]);
            $newTeacher->setBbsid();

            $this->teacherRepository->update($newTeacher);

        } else { // Wenn es ein Schüler ist
            $newStudent = $account->getParty();
            $newStudent->setFname($userarray['givenname'][0]);
            $newStudent->setLname($userarray['sn'][0]);
            $newStudent->setEmail($userarray['mail'][0]);
            $newStudent->setClass($userarray['bbsmemberofclass'][0]);
            $newStudent->setBbsid($userarray['bbsid'][0]);

            $this->studentRepository->update($newStudent);
        }
    }

}

?>
