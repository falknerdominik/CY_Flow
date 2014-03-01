<?php
namespace BBS\ConnectYou\Service;

use TYPO3\Flow\Annotations as Flow;

/**
 * A simple LDAP authentication service
 * @Flow\Scope("prototype")
 */
class DirectoryService {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @var \BBS\ConnectYou\Service\BindProvider\BindProviderInterface
	 */
	protected $bindProvider;

	/**
	 * @param $name
	 * @param array $options
	 * @return void
	 */
	public function __construct($name, array $options) {
		$this->name = $name;
		$this->options = $options;

		if (!extension_loaded('ldap')) { // Wenn Ldap Modul nicht geladen ist
			throw new \TYPO3\Flow\Error\Exception('PHP is not compiled with LDAP support', 1305406047);
		}
	}

	/**
	 * Initalisiere die Verbindung
	 *
	 *
	 * @throws \TYPO3\Flow\Error\Exception
	 */
	public function ldapConnect() {

		$bindProviderClassName = 'BBS\ConnectYou\Service\BindProvider\\' . $this->options['type'] . 'Bind'; // Auswählen des Binds

		if (!class_exists($bindProviderClassName)) {
			throw new \TYPO3\Flow\Error\Exception('Kein Bind Provider"' . $this->options['type'] . '" gefunden.', 1327756744);
		}

		try {
			$connection = ldap_connect($this->options['host'], $this->options['port']); // Versuche die Verbindung
			$this->bindProvider = new $bindProviderClassName($connection, $this->options); // Init den Bind Provider
			$this->setLdapOptions();
		} catch (\Exception $exception) {
			throw new \TYPO3\Flow\Error\Exception('Konnte nicht zum LDAP Verbinden.', 1326985286);
		}
	}

	/**
	 * Setzt die LDAP Konfiguration aus Settings.yaml
     *
	 * Beispiel in settings.yaml:
	 *  protocol_version: 3
	 * Wird zu:
	 *  LDAP_OPT_PROTOCOL_VERSION 3
	 *
	 * @return void
	 */
	protected function setLdapOptions() {
        // Prüft ob Eigenschaften vorhanden sind und diese in einem array sind
		if (!empty($this->options['ldapOptions']) && is_array($this->options['ldapOptions'])) {
            // Schleife die durch alle Optionen geht
			foreach ($this->options['ldapOptions'] as $ldapOption => $ldapOptionValue) {
                // Hier wierd die Eigenschaft wieder zusammengebaut
				$constantName = 'LDAP_OPT_' . strtoupper($ldapOption);
                // Setzt die Option im bindProvider
				ldap_set_option($this->bindProvider->getLinkIdentifier(), constant($constantName), $ldapOptionValue);
			}
		}
	}

	/**
	 * Authentifizierung gegen Server
	 *
	 * @param string $username
	 * @param string $password
	 * @return array Search result from LDAP
	 * @throws \TYPO3\Flow\Error\Exception
	 */
	public function authenticate($username, $password) { // Hier wird versucht den User zu Authentifizieren
		try {
			$this->ldapConnect(); // Verbindung aufbauen
			$this->bindProvider->bind($username, $password); // Bind
			$entries = $this->getUserEntries($username); // Versuche
			if (!empty($entries)) { // Wenn einträge im AD gefunden wurden
				$this->bindProvider->verifyCredentials($entries[0]['dn'], $password); // Überprüft die Logindaten nochmalig
				$entries = $this->getUserEntries($username); // Da kein Anonymer bind erneut die entries auslesen
			}
			return $entries[0]; // Return den userentry
		} catch (\Exception $exception) {
			throw new \TYPO3\Flow\Error\Exception('Fehler während der Authentifizierung: ' . $exception->getMessage(), 1323167213);
		}
	}

	/**
	 * Get the user entities from the LDAP server
	 * At least the dn should be returned.
	 *
	 * @param $username
	 * @return array
	 */
	public function getUserEntries($username) {
        try{
            $searchResult = ldap_search( // Durchsuche AD
                $this->bindProvider->getLinkIdentifier(), // ds
                $this->options['baseDn'], // BASE DN = dc=bbsrb, dc=local
                str_replace( // Benutzername
                    '?',
                    $this->bindProvider->getFilteredUsername($username),
                    $this->options['filter']['account']
                )
            );
            if ($searchResult) { // Wenn etwas gefunden wurde
                $entries = ldap_get_entries($this->bindProvider->getLinkIdentifier(), $searchResult);

                if ($entries['count'] === 1) { // Wenn ein Eintrag gefunden wurde
                    return $entries;
                }
            }
        } catch(\Exception $e){
            // Debugging vom Userarray hier
        }
	}

	/**
	 * Check den Server mithilfe eines Utilitys
	 *
	 * @return boolean
	 */
	public function isServerOnline() { // Prüft ob Server erreichbar
		return \BBS\Connectyou\Utility\ServerStatusUtility::isServerOnline($this->options['host'], $this->options['port']);
	}

}

?>