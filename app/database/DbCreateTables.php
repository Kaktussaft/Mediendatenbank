<?php

require_once 'DbConnection.php';
use App\Database\DbConnection;

$conn = DbConnection::getInstance()->getConnection();

#---------------------------------------------------------------------------
#						Erstelle Datenbank Mediendatenbank
#---------------------------------------------------------------------------

// SQL-Befehl zum Erstellen der Datenbank
//$sql = "CREATE DATABASE $database";

// Check
// if ($conn->query($sql) === TRUE) {
//     echo "Datenbank erfolgreich erstellt";
// } else {
//     echo "Fehler beim Erstellen der Datenbank: " . $conn->error;
// }

// Datenbank auswählen
// if ($conn->select_db($database)) {
//     echo "Datenbank erfolgreich ausgewählt: $database";
// } else {
//     echo "Fehler beim Auswählen der Datenbank: " . $conn->error;
// }



#---------------------------------------------------------------------------
#						Erstelle Benutzer-Tabelle
#---------------------------------------------------------------------------

$sql = "CREATE TABLE Benutzer 
(Benutzer_ID int auto_increment primary key, 
Benutzername varchar(20) not null, 
EMail varchar(30) not null, 
Nachname varchar(30) not null, 
Vorname varchar(30) not null, 
Rolle varchar(10) not null, 
Registrierungsdatum date not null)";

if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich erstellt";
} else {
    echo "Fehler beim Erstellen der Tabelle: " . $conn->error;
}

#---------------------------------------------------------------------------
#						Erstelle Schlagworte-Tabelle
#---------------------------------------------------------------------------

$sql = "CREATE TABLE Schlagworte (
    Schlagwort_ID int auto_increment primary key, 
    Schlagwort_Name varchar(20) not null,
    Benutzer_ID int not null, 
    foreign key (Benutzer_ID) references Benutzer(Benutzer_ID)
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich erstellt";
} else {
    echo "Fehler beim Erstellen der Tabelle: " . $conn->error;
}

#---------------------------------------------------------------------------
#						Erstelle Ebooks-Tabelle
#---------------------------------------------------------------------------

$sql = "CREATE TABLE Ebooks (
    ebook_ID CHAR(36) PRIMARY KEY,
    Titel varchar(30) not null, 
    Dateipfad varchar(80), 
    Typ varchar(10) not null, 
    Dateigröße varchar(20) not null, 
    Hochlade_datum date not null, 
    Autor varchar(30), 
    Seitenzahl int, 
    Benutzer_ID int not null, 
    foreign key (Benutzer_ID) references Benutzer(Benutzer_ID)
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich erstellt";
} else {
    echo "Fehler beim Erstellen der Tabelle: " . $conn->error;
}

#---------------------------------------------------------------------------
#						Erstelle Hörbücher-Tabelle
#---------------------------------------------------------------------------

$sql = "CREATE TABLE Hörbücher (
    Hörbuch_ID CHAR(36) PRIMARY KEY,
    Titel varchar(30) not null, 
    Dateipfad varchar(80), 
    Typ varchar(10) not null, 
    Dateigröße varchar(20) not null, 
    Hochlade_datum date not null, 
    Sprecher varchar(30), 
    Dauer time, 
    Benutzer_ID int not null, 
    foreign key (Benutzer_ID) references Benutzer(Benutzer_ID)
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich erstellt";
} else {
    echo "Fehler beim Erstellen der Tabelle: " . $conn->error;
}

#---------------------------------------------------------------------------
#						Erstelle Videos-Tabelle
#---------------------------------------------------------------------------

$sql = "CREATE TABLE Videos (
    Video_ID CHAR(36) PRIMARY KEY,
    Titel varchar(30) not null, 
    Dateipfad varchar(80), 
    Typ varchar(10) not null, 
    Dateigröße varchar(20) not null, 
    Hochlade_datum date not null, 
    Auflösung varchar(10), 
    Dauer time, 
    Benutzer_ID int not null, 
    foreign key (Benutzer_ID) references Benutzer(Benutzer_ID)
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich erstellt";
} else {
    echo "Fehler beim Erstellen der Tabelle: " . $conn->error;
}

#---------------------------------------------------------------------------
#						Erstelle Fotos-Tabelle
#---------------------------------------------------------------------------

$sql = "CREATE TABLE Fotos (
    Foto_ID CHAR(36) PRIMARY KEY,
    Titel varchar(30) not null, 
    Dateipfad varchar(80), 
    Typ varchar(10) not null, 
    Dateigröße varchar(20) not null, 
    Hochlade_datum date not null,
    Auflösung varchar(10), 
    Benutzer_ID int not null, 
    foreign key (Benutzer_ID) references Benutzer(Benutzer_ID)
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich erstellt";
} else {
    echo "Fehler beim Erstellen der Tabelle: " . $conn->error;
}

#---------------------------------------------------------------------------
#						Erstelle SchlagwortMedien-Tabelle (n-m-Beziehung)
#---------------------------------------------------------------------------

$sql = "CREATE TABLE SchlagwortMedien 
(Schlagwort_ID int not null, foreign key(Schlagwort_ID) References Schlagworte(Schlagwort_ID))";

if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich erstellt";
} else {
    echo "Fehler beim Erstellen der Tabelle: " . $conn->error;
}

# Weiter mit Alter table weil das sonst mit den ganzen Fremdschlüsseln so ein Salat wurde. Keine Ahnung, ob das überhaupt als ein Befehl geht
$sql = "ALTER TABLE SchlagwortMedien ADD COLUMN ebook_ID CHAR(36), ADD foreign key(ebook_ID) References ebooks(ebook_ID)";

if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich geändert und Fremdschlüssel gesetzt.";
} else {
    echo "Fehler beim Ändern der Tabelle: " . $conn->error;
}
$sql = "ALTER TABLE SchlagwortMedien ADD COLUMN Hörbuch_ID CHAR(36), ADD foreign key(Hörbuch_ID) References Hörbücher(Hörbuch_ID)";
if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich geändert und Fremdschlüssel gesetzt.";
} else {
    echo "Fehler beim Ändern der Tabelle: " . $conn->error;
}
$sql = "ALTER TABLE SchlagwortMedien ADD COLUMN Video_ID CHAR(36), ADD foreign key(Video_ID) References Videos(Video_ID)";
if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich geändert und Fremdschlüssel gesetzt.";
} else {
    echo "Fehler beim Ändern der Tabelle: " . $conn->error;
}
$sql = "ALTER TABLE SchlagwortMedien ADD COLUMN Foto_ID CHAR(36), ADD foreign key(Foto_ID) References Fotos(Foto_ID)";
if ($conn->query($sql) === TRUE) {
    echo "Tabelle erfolgreich geändert und Fremdschlüssel gesetzt.";
} else {
    echo "Fehler beim Ändern der Tabelle: " . $conn->error;
}


#---------------------------------------------------------------------------

// Verbindung schließen
$conn->close();

?>







