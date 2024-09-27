# WEB42
The site implements a Modell-View-Controller architectur with an additional abstraction layer of a Repository, 
that handles the direct interaction with the database. 

Datenbank
DBconnection definiert die Verbindung. Dort muss soweit nichts geändert werden, es sei denn es besteht der Wunsch den festgesetzten
Namen der Datenbank (momentan "Mediendatenbank") zu ändern. 

eine dummy Datenbank wird im zip file bereitgestellt und kann über phpmyadmin importiert werden. Die Datenbank
wird dann auch automatisch erstellt und muss nicht über DbCreateTable erstellt werden. 
app\database\mediendatenbank.sql   

Das aufsetzten funktioniert wie folgt:
in phpmyadmin einen neue Datenbank mit dem Namen "Mediendatenbank" erstellen
im Terminal in den Ordner database navigieren
den Command : php.DbCreateTables.php ausführen
Mit DropTables.php kann diese bei Bedarf auch wieder entfernt werden

Composer
Das benötigte file für den autoload: composer.json ist schon vorhanden
Composer kann hier heruntergeladen werden: https://getcomposer.org/download/
danach kann man mit composer install im Terminal die benötigten Ordner für die Autload funktionalität generieren
