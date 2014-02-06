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
			if ($this->directoryService->isServerOnline()) { // Checked ob der Server Online ist
				try { // Versucht die Anmeldung
					$ldapUser = $this->directoryService->authenticate($credentials['username'], $credentials['password']);

					if ($ldapUser) { // Wenn der ldapUser gebunden werden konnte
						$account = $this->accountRepository->findActiveByAccountIdentifierAndAuthenticationProviderName($credentials['username'], $this->name);
						if (empty($account)) { // Wenn der Account NICHT in der DB vorhanden ist erstelle ihn
							$account = new \TYPO3\Flow\Security\Account(); // Neuer Account
							$account->setAccountIdentifier($credentials['username']); // Setzt Username
							$account->setAuthenticationProviderName($this->name); // Setzt Providername
							$this->accountRepository->add($account); // Fügt den Account hinzu

							$this->createParty($account, $ldapUser); // Erstellt die Party
						}

						if ($account instanceof \TYPO3\Flow\Security\Account) { // Wenn ein Account vorhanden ist
							$account->setCredentialsSource($this->hashService->generateHmac($credentials['password'])); // Passwort Cachen
							$authenticationToken->setAuthenticationStatus(TokenInterface::AUTHENTICATION_SUCCESSFUL); // Login Successful
							$authenticationToken->setAccount($account); // Setzt den Account
						} elseif ($authenticationToken->getAuthenticationStatus() !== TokenInterface::AUTHENTICATION_SUCCESSFUL) { // Vergleicht ob schon eingeloggt
							$authenticationToken->setAuthenticationStatus(TokenInterface::NO_CREDENTIALS_GIVEN); // sonst NO_CREDENTIALS_GIVEN
						}
					} else {
						$authenticationToken->setAuthenticationStatus(TokenInterface::WRONG_CREDENTIALS); // Letzte Möglichkeit ... falsche Crendentials
					}

				} catch (\Exception $exception) {
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
			$authenticationToken->setAuthenticationStatus(TokenInterface::NO_CREDENTIALS_GIVEN);
		}
	}

	/**
	 * Erstellt den Student oder Leherer Party
	 *
	 * @param \TYPO3\Flow\Security\Account $account The freshly created account that should be used for this party
	 * @param array $ldapSearchResult The first result returned by ldap_search
	 * @return void
	 */
	protected function createParty(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
        // TODO Student / Teacher installieren
	}

}

?>