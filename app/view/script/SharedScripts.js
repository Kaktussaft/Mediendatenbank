function initModal(modalId, openButtonId, closeButtonId) {
    const modal = document.getElementById(modalId);
    const openModalLink = document.getElementById(openButtonId);
    const closeModalButton = document.getElementById(closeButtonId);

    // Event-Listener für das Öffnen des Modals
    openModalLink.addEventListener('click', (event) => {
        event.preventDefault(); // Verhindert das Standardverhalten des Links
        modal.style.display = 'block'; // Zeigt das Modal an
    });

    // Event-Listener für das Schließen des Modals
    closeModalButton.addEventListener('click', () => {
        modal.style.display = 'none'; // Versteckt das Modal
    });

    // Schließen des Modals bei Klick außerhalb des Inhaltsbereichs
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none'; // Versteckt das Modal
        }
    });
}
function routeLogout(event) {
    event.preventDefault();
    console.log('Logout clicked');
    window.location.href = '/Mediendatenbank/public/UserController/logout/';

}

function updateUserNonAdmin() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const lastname = document.getElementById('lastname').value;
    const firstname = document.getElementById('firstname').value;

    fetch('http://localhost/Mediendatenbank/public/UserController/updateUser', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            username: name,
            email: email,
            lastname: lastname,
            firstname: firstname,
        })
    })
    .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function updateUserAdmin() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const lastname = document.getElementById('lastname').value;
    const firstname = document.getElementById('firstname').value;
    const updateUser = document.getElementById('accountSelection').value;
    const isAdmin = document.querySelector('input[name="isAdmin"]:checked').value;

    fetch('http://localhost/Mediendatenbank/public/UserController/updateUserAdmin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            username: name,
            oldUsername: updateUser,
            email: email,
            lastname: lastname,
            firstname: firstname,
            isAdmin: isAdmin,
        })
    })
    .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function deleteUser(){
    const deleteUser = document.getElementById('accountSelection').value;

    fetch('http://localhost/Mediendatenbank/public/UserController/deleteUser', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            username: deleteUser,
        })
    })
    .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function getSorting(){
    let sorting = {};

    const parameter = document.getElementById('sortingcriteria').value;
    const order = document.querySelector('input[name="sortingorder"]:checked').value
    
    sorting.parameter = parameter;
    sorting.order = order;

    return sorting;
}

function openMedium(mediumSrc, title, mediumID) {
    const modal = document.getElementById('mediumModal');
    const modalMedium = document.getElementById('modalMedium');
    const caption = document.getElementById('caption');
    const closeModalButton = document.getElementById('close-mediumModal');
    

    modal.style.display = 'block'; // Modal anzeigen
    modalMedium.src = mediumSrc; // Setze das Bild im Modal
    modalMedium.value = mediumID;
    caption.innerHTML = title; // Setze den Bildtitel im Modal

    const modifyButton = document.createElement('button');
    modifyButton.textContent = 'Bearbeiten';
    modifyButton.id = 'open-modifyMedium-modal'
    caption.appendChild(modifyButton);

    closeModalButton.addEventListener('click', () => {
        modal.style.display = 'none'; // Versteckt das Modal
    });
    // Schließen des Modals bei Klick außerhalb des Bildes
    modal.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none'; // Modal verstecken
        }
    };
}

function updateMedium(){
    const mediumID = document.getElementById('modalMedium').value;
    const mediumTitle = document.getElementById('newTitle').value;

    fetch('http://localhost/Mediendatenbank/public/MediumController/updateMedium', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            ID: mediumID,
            Titel: mediumTitle,
        })
    })
        .then(loadAll())
        .catch(error => console.error('Fehler beim Bearbeiten des Mediums:', error));
}

function loadAll(){
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
        })
    })
        .then(response => response.json())
        .then(data => {
            const contentArea = document.getElementById('contentArea');
            contentArea.innerHTML = '';
            Object.keys(data.data).forEach(type => {
                const mediaTypeList = data.data[type];
                mediaTypeList.forEach(medium => {
                    
                    const element = document.createElement('img');
                    switch (type){
                        case 'Fotos':
                            element.src = medium.Dateipfad;
                            element.id = medium.Foto_ID;
                            break;
                        case 'Videos':
                            element.src = '/Mediendatenbank/public/placeholders/placeholder_video.jpg';
                            element.id = medium.Video_ID;
                            break;
                        case 'Ebooks':
                            element.src = '/Mediendatenbank/public/placeholders/placeholder_ebook.jpg';
                            element.id = medium.ebook_ID;
                            break;
                        case 'Hörbücher':
                            element.src = '/Mediendatenbank/public/placeholders/placeholder_audiobook.jpg';
                            element.id = medium.Hörbuch_ID;
                            break;
                    }
                    
                    element.alt = medium.Titel || 'Kein Titel';
                    contentArea.appendChild(element);

                    element.addEventListener('click', function() {
                        openMedium(medium.Dateipfad, medium.Titel || 'Kein Titel', element.id);
                        initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                    });
                })
            });
            
        })
        .catch(error => console.error('Fehler beim Laden der Bilder:', error));
}

function loadPhotos() {
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
        })
    })
        .then(response => response.json())
        .then(data => {
            const bildContainer = document.getElementById('contentArea');
            bildContainer.innerHTML = '';
            const photos = data.data['Fotos'];

            photos.forEach(bild => {
                const img = document.createElement('img');
                img.src = bild.Dateipfad;
                img.alt = bild.Titel;
                bildContainer.appendChild(img);
            });
        })
        .catch(error => console.error('Fehler beim Laden der Bilder:', error));
}

function loadVideos() {
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
        })
    })
        .then(response => response.json())
        .then(data => {
            const contentContainer = document.getElementById('contentArea');
            contentContainer.innerHTML = '';
            const videos = data.data['Videos'];

            videos.forEach(video => {
                const vid = document.createElement('img');
                vid.src = '/Mediendatenbank/public/placeholders/placeholder_video.jpg';
                vid.alt = video.Titel;
                contentContainer.appendChild(vid);
            });
        })
        .catch(error => console.error('Fehler beim Laden der Videos:', error));
}

function loadEbooks() {
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
        })
    })
        .then(response => response.json())
        .then(data => {
            const contentContainer = document.getElementById('contentArea');
            contentContainer.innerHTML = '';
            const ebooks = data.data['Ebooks'];

            ebooks.forEach(ebook => {
                const ebk = document.createElement('img');
                ebk.src = '/Mediendatenbank/public/placeholders/placeholder_ebook.jpg';
                ebk.alt = ebook.Titel;
                contentContainer.appendChild(ebk);
            });
        })
        .catch(error => console.error('Fehler beim Laden der E-Books:', error));
}

function loadAudioBooks() {
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
        })
    })
        .then(response => response.json())
        .then(data => {
            const contentContainer = document.getElementById('contentArea');
            contentContainer.innerHTML = '';
            const audiobooks = data.data['Hörbücher'];

            audiobooks.forEach(audiobook => {
                const abk = document.createElement('img');
                abk.src = '/Mediendatenbank/public/placeholders/placeholder_audiobook.jpg';
                abk.alt = audiobook.Titel;
                contentContainer.appendChild(abk);
            });
        })
        .catch(error => console.error('Fehler beim Laden der Hörbücher:', error));
}

function loadKeyWords(keyWordElement, listType, deletionButton){
    fetch('http://localhost/Mediendatenbank/public/KeywordController/getAllKeywordsAndAssociations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status = 'success'){
                const keyWords = data.data[0];
                const keyWordList = document.getElementById(keyWordElement);
                keyWordList.innerHTML = '';

                switch (listType) {
                    case "checkbox":
                    keyWords.forEach(keyword => {
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'keywords[]';
                        checkbox.value = keyword.Schlagwort_ID;
        
                        const label = document.createElement('label');
                        const labelText = document.createTextNode(" " + keyword.Schlagwort_Name); // Nutze den Keyword Namen als Label
                        label.appendChild(labelText);
                        
                        keyWordList.appendChild(checkbox);
                        keyWordList.appendChild(label);
                        
                        if (deletionButton){
                            const deleteButton = document.createElement('button');
                            deleteButton.textContent = 'Löschen';  // Set button text
                            deleteButton.onclick = function() {
                                deleteKeyword(keyword.Schlagwort_ID);
                                refreshKeyWords();
                            };
                            keyWordList.appendChild(deleteButton);
                        }               
                        
                        keyWordList.appendChild(document.createElement('br')); // Für Zeilenumbruch
                    });
                    break;

                    case "select":
                        const defaultOption = document.createElement('option');
                        defaultOption.text = 'Bitte Schlagwort auswählen';
                        defaultOption.value = '';
                        keyWordList.appendChild(defaultOption);

                        keyWords.forEach(keyword => {
                            const option = document.createElement('option');
                            option.value = keyword.Schlagwort_ID;
                            option.text = keyword.Schlagwort_Name;
                            keyWordList.appendChild(option);
                        });
                    break;

                    default:
                        console.error('Bitte Listentyp eintragen.');
                }

                
            } else {
                console.error('Fehler beim Laden der Schlagworte:', data.message);
            }            
        })
        .catch(error => console.error('Fehler beim Laden der Schlagworte:', error));
}

function refreshKeyWords(){
    loadKeyWords('keyWordList', 'checkbox', false);
    loadKeyWords('modifyKeyWordList', 'checkbox', true);
    loadKeyWords('keyWordSelection', 'select', false);
    loadKeyWords('mediumKeywords', 'checkbox', false);
}

function createKeyWord(keywordName) {
    fetch('http://localhost/Mediendatenbank/public/KeywordController/createKeyword', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            keywordName: keywordName,
        })
    })
    //.then(response => response.json())
    //.then(data => {
    //    if (data.status === 'success') {
    //        alert('Schlagwort erfolgreich erstellt.');
    //        loadKeyWords('keyWordElement');  // Reload the list after deletion
    //    } else {
    //        alert('Fehler beim Anlegen des Schlagworts: ' + data.message);
    //    }
    //})
    .catch(error => console.error('Fehler beim Anlegen des Schlagworts:', error));
}

function updateKeyWord(keyWordId, newKeyWordName){
    fetch('http://localhost/Mediendatenbank/public/KeywordController/updateKeyword', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            keywordId: keyWordId,
            keywordName: newKeyWordName,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Schlagwort erfolgreich bearbeitet.');
        } else {
            alert('Fehler beim Bearbeiten des Schlagworts: ' + data.message);
        }
    })
    .catch(error => console.error('Fehler beim Bearbeiten des Schlagworts:', error));
}

function deleteKeyword(keywordId){
    if (confirm("Möchten Sie dieses Schlagwort wirklich löschen?")) {
        fetch('http://localhost/Mediendatenbank/public/KeywordController/deleteKeyword', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                keywordId: keywordId,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Schlagwort erfolgreich gelöscht.');
            } else {
                alert('Fehler beim Löschen des Schlagworts: ' + data.message);
            }
        })
        .catch(error => console.error('Fehler beim Löschen des Schlagworts:', error));
    }
}

function loadUsers(listId){
    const userList = document.getElementById(listId);
    fetch('http://localhost/Mediendatenbank/public/UserController/getAllUsers', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            const users = data;
            
            const defaultOption = document.createElement('option');
            defaultOption.text = 'Bitte User auswählen';
            defaultOption.value = '';
            defaultOption.hidden = true;
            userList.appendChild(defaultOption);

            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.Benutzername;
                option.text = user.Benutzername;
                userList.appendChild(option);
            });
        
        })
}

async function countUsers(){
    let userCount = 0;
    const response = await fetch('http://localhost/Mediendatenbank/public/UserController/getAllUsers', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json();
    userCount = data.length;

    return userCount;
}

async function getAllUsers(){
    let users = []
    const response = await fetch('http://localhost/Mediendatenbank/public/UserController/getAllUsers', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json();
    users = data;

    return users;
}

async function loadDashboard(){
    const userCount = await countUsers();
    const users = await getAllUsers();
    const fetchPromises = [];
    const dbstatsarea = document.getElementById('dbStats');
    const userstatsarea = document.getElementById('userStats');
    let photoCount = 0;
    let photoCountUser = 0;
    let videoCount = 0;
    let videoCountUser = 0;
    let ebookCount = 0;
    let ebookCountUser = 0;
    let audiobookCount = 0;
    let audiobookCountUser = 0;
    let allKeyWordsCount = 0;
    let keyWordCountUser = 0;
    const userMediaCounts = [];

    users.forEach(user => {
        photoCountUser = 0;
        videoCountUser = 0;
        ebookCountUser = 0;
        audiobookCountUser = 0;
        keyWordCountUser = 0;

        const fetchPromiseMedia = fetch('http://localhost/Mediendatenbank/public/MediumController/getMediaAmountPerUser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                userId: user.Benutzer_ID,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Object.keys(data.data).forEach(type => {
                    const mediumCount = data.data[type][0]["COUNT(*)"];
                    console.log(type + ' des Benutzers ' + user.Benutzer_ID + ": " + mediumCount);
                    switch (type){
                        case "Fotos":
                            photoCount = photoCount + mediumCount;
                            photoCountUser = mediumCount;
                            break;
                        case "Videos":
                            videoCount = videoCount + mediumCount;
                            videoCountUser = mediumCount;
                            break;
                        case "Ebooks":
                            ebookCount = ebookCount + mediumCount;
                            ebookCountUser = mediumCount;
                            break;
                        case "Hörbücher":
                            audiobookCount = audiobookCount + mediumCount;
                            audiobookCountUser = mediumCount;
                            break;
                        default:
                            console.log('Nicht unterstützter Type: ' + type);
                            break;
                    }
                    })
                
            } else {
                console.log('Fehler beim Laden der Medien des Benutzers ' + user.Benutzer_ID + ': ' + data.message);
            }
        })
        .catch(error => console.error('Fehler beim Laden der Benutzermedien:', error));

        const fetchPromiseKeyWords = fetch('http://localhost/Mediendatenbank/public/KeywordController/readKeywordPerUser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                userId: user.Benutzer_ID,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                    const keywordCount = data.data[0]["COUNT(Schlagwort_ID)"];

                    allKeyWordsCount = allKeyWordsCount + keywordCount;
                    keyWordCountUser = keywordCount;
                
            } else {
                console.log('Fehler beim Laden der Medien des Benutzers ' + user.Benutzer_ID + ': ' + data.message);
            }
        })
        .catch(error => console.error('Fehler beim Laden der Benutzermedien:', error));
        
        fetchPromises.push(
            Promise.all([fetchPromiseMedia, fetchPromiseKeyWords]).then(() => {
                userMediaCounts.push({
                    username: user.Benutzername,
                    photoCountUser,
                    videoCountUser,
                    ebookCountUser,
                    audiobookCountUser,
                    keyWordCountUser
                });
            })
        );
    });
    Promise.all(fetchPromises)
        .then(() => {
            const table = document.createElement('table');

            // Tabellenkopf erstellen (Medientypen)
            const header = table.createTHead();
            const headerRow = header.insertRow(0); // Erste Zeile für die Spaltenüberschriften
            const headers = ['Fotos', 'Videos', 'Ebooks', 'Hörbücher', 'Schlagwörter'];
            headers.forEach((type) => {
                const cell = document.createElement('th');
                cell.textContent = type;
                headerRow.appendChild(cell);
            });

            // Tabellenkörper erstellen und die Zähler für die Medien-Daten einfügen
            const tbody = table.createTBody();
            const valueRow = tbody.insertRow(); // Zeile für die Zählerwerte
            const counts = [photoCount, videoCount, ebookCount, audiobookCount, allKeyWordsCount];
            counts.forEach(count => {
                const cell = valueRow.insertCell();
                cell.textContent = count;
            });

            dbstatsarea.innerHTML = ''; // Leere das Div zuerst
            dbstatsarea.appendChild(table);

            console.log('Medien gesamt nach Typ: Fotos: ' + photoCount + ', Videos: ' + videoCount + ', Ebooks: ' + ebookCount + ', Hörbücher: ' + audiobookCount);
            console.log('Anzahl aller Schlagwörter: ' + allKeyWordsCount);

            const userTable = document.createElement('table');
            const userHeader = userTable.createTHead();
            const userHeaderRow = userHeader.insertRow(0);

            // Tabellenkopf erstellen
            ['Username', 'Fotos', 'Videos', 'Ebooks', 'Hörbücher', 'Schlagwörter'].forEach(type => {
                const cell = document.createElement('th');
                cell.textContent = type;
                userHeaderRow.appendChild(cell);
            });

            const userBody = userTable.createTBody();
            userMediaCounts.forEach(userData => {
                const row = userBody.insertRow();
                const usernameCell = row.insertCell();
                usernameCell.textContent = userData.username;

                [userData.photoCountUser, userData.videoCountUser, userData.ebookCountUser, userData.audiobookCountUser, userData.keyWordCountUser].forEach(count => {
                    const cell = row.insertCell();
                    cell.textContent = count;
                });
            });

            userstatsarea.innerHTML = ''; // Vorherige Inhalte leeren
            userstatsarea.appendChild(userTable);
        })
        .catch(error => {
            console.error('Ein Fehler ist aufgetreten:', error);
        });
}
