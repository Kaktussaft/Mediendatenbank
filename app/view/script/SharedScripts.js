function initModal(modalId, openButtonId, closeButtonId) {
    const modal = document.getElementById(modalId);
    const openModalLink = document.getElementById(openButtonId);
    const closeModalButton = document.getElementById(closeButtonId);

    openModalLink.addEventListener('click', (event) => {
        event.preventDefault();
        modal.style.display = 'block';
    });
    closeModalButton.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
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
    

    modal.style.display = 'block';
    modalMedium.src = mediumSrc;
    modalMedium.value = mediumID;
    caption.innerHTML = title;

    const modifyButton = document.createElement('button');
    modifyButton.textContent = 'Bearbeiten';
    modifyButton.id = 'open-modifyMedium-modal'
    caption.appendChild(modifyButton);

    closeModalButton.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    
    modal.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };

    loadKeyWords('mediumKeywords', 'checkbox', false);
}

async function isChecked(mediumID, keywordID){
    const associations = await getAssociation(mediumID);

    return associations.some(association => association.Schlagwort_ID === keywordID);
    
}

async function getSelectedKeywordsWithAssociation(){
    let selectedKeywords = [];
    
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
            const associations = data.data[1];

            keyWords.forEach(keyword =>{
                const checkbox = document.getElementById(keyword.Schlagwort_ID);
                const existsInAssociations = associations.some(item => item.Schlagwort_ID === keyword.Schlagwort_ID);

                    if (checkbox && checkbox.checked){
                        if (existsInAssociations){
                            associations.forEach(association => {
                                if (association.Schlagwort_ID == checkbox.id){
                                    selectedKeywords.push(association);
                                }
                            })
                        } else {
                            selectedKeywords.push({
                                "Schlagwort_ID": keyword.Schlagwort_ID,
                                "Foto_ID": null,
                                "Video_ID": null,
                                "Hörbuch_ID": null,
                                "ebook_ID": null,
                            })
                        }   
                    }
            })
        } else {
            console.error('Fehler beim Laden der ausgewählten Schlagworte:', data.message);
        }            
    })
    .catch(error => console.error('Fehler beim Laden der ausgewählten Schlagworte:', error));

    return selectedKeywords;
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

function deleteMedium(){
    const mediumID = document.getElementById('modalMedium').value;

    fetch('http://localhost/Mediendatenbank/public/MediumController/deleteMedium', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            ID: mediumID,
        })
    })
        .then(loadAll())
        .catch(error => console.error('Fehler beim Löschen des Mediums:', error));
}

async function loadAll(){
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;
    const searchParameter = document.getElementById('searchBar').value;
    const selectedKeywords = await getSelectedKeywordsWithAssociation();

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
            searchParameter: searchParameter,
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
                    let lastKeywordId = 0;
                    element.alt = medium.Titel;
                    element.title = medium.Titel;
                    
                    switch (type){
                        case 'Fotos':
                            element.src = medium.Dateipfad;
                            element.id = medium.Foto_ID;
                            break;
                        case 'Videos':
                            element.src = '/Mediendatenbank/public/placeholders/placeholder_video.png';
                            element.id = medium.Video_ID;
                            break;
                        case 'Ebooks':
                            element.src = '/Mediendatenbank/public/placeholders/placeholder_ebook.png';
                            element.id = medium.ebook_ID;
                            break;
                        case 'Hörbücher':
                            element.src = '/Mediendatenbank/public/placeholders/placeholder_audiobook.png';
                            element.id = medium.Hörbuch_ID;
                            break;
                    }
                    
                    element.alt = medium.Titel || 'Kein Titel';
                    if (selectedKeywords.length > 0){
                        
                        let inAll = true;
                        
                        let objectHandled = false;
                        selectedKeywords.forEach(association =>{
                            if (association.Schlagwort_ID != lastKeywordId){
                                lastKeywordId = association.Schlagwort_ID;
                                
                                objectHandled = false;

                                if(inAll === false){
                                    objectHandled = true;
                                }
                                
                                if(!Object.values(association).includes(element.id)){
                                    inAll = false;
                                } else {
                                    objectHandled = true;
                                }
                            } else {
                                if (Object.values(association).includes(element.id) && !objectHandled) {
                                    inAll = true;
                                    objectHandled = true;
                                }
                                else if(!Object.values(association).includes(element.id) && !objectHandled){
                                    inAll = false;
                                } 
                            }
                        })
                        if (inAll){
                            contentArea.appendChild(element);
                            element.addEventListener('click', function() {
                            openMedium(medium.Dateipfad, medium.Titel || 'Kein Titel', element.id);
                            initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                        });
                        }
                    } else {
                        contentArea.appendChild(element);
                        element.addEventListener('click', function() {
                            openMedium(medium.Dateipfad, medium.Titel || 'Kein Titel', element.id);
                            initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                        });
                    }
                    
                })
            });
            
        })
        .catch(error => console.error('Fehler beim Laden der Bilder:', error));
}

async function loadPhotos() {
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;
    const searchParameter = document.getElementById('searchBar').value;
    const selectedKeywords = await getSelectedKeywordsWithAssociation();

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
            searchParameter: searchParameter,
        })
    })
        .then(response => response.json())
        .then(data => {
            const contentContainer = document.getElementById('contentArea');
            contentContainer.innerHTML = '';
            const photos = data.data['Fotos'];

            photos.forEach(bild => {
                const img = document.createElement('img');
                let lastKeywordId = 0;
                img.src = bild.Dateipfad;
                img.alt = bild.Titel;
                img.title = bild.Titel;
                img.id = bild.Foto_ID;

                if (selectedKeywords.length > 0){
                        
                    let inAll = true;
                    
                    let objectHandled = false;
                    selectedKeywords.forEach(association =>{
                        if (association.Schlagwort_ID != lastKeywordId){
                            lastKeywordId = association.Schlagwort_ID;
                                
                                objectHandled = false;

                                if(inAll === false){
                                    objectHandled = true;
                                }
                                
                                if(!Object.values(association).includes(img.id)){
                                    inAll = false;
                                } else {
                                    objectHandled = true;
                                }
                        } else {
                            if (Object.values(association).includes(img.id) && !objectHandled) {
                                inAll = true;
                                objectHandled = true;
                            }
                            else if(!Object.values(association).includes(img.id) && !objectHandled){
                                inAll = false;
                            } 
                        }
                    })
                    if (inAll){
                        contentContainer.appendChild(img);
                        img.addEventListener('click', function() {
                        openMedium(img.src, img.alt || 'Kein Titel', img.id);
                        initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                    });
                    }
                } else {
                    contentContainer.appendChild(img);
                    img.addEventListener('click', function() {
                        openMedium(img.src, img.alt || 'Kein Titel', img.id);
                        initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                    });
                }
            });
        })
        .catch(error => console.error('Fehler beim Laden der Bilder:', error));
}

async function loadVideos() {
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;
    const searchParameter = document.getElementById('searchBar').value;
    const selectedKeywords = await getSelectedKeywordsWithAssociation();

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
            searchParameter: searchParameter,
        })
    })
        .then(response => response.json())
        .then(data => {
            const contentContainer = document.getElementById('contentArea');
            contentContainer.innerHTML = '';
            const videos = data.data['Videos'];

            videos.forEach(video => {
                const vid = document.createElement('img');
                let lastKeywordId = 0;
                vid.src = '/Mediendatenbank/public/placeholders/placeholder_video.png';
                vid.alt = video.Titel;
                vid.title = video.Titel;
                vid.id = video.Video_ID;
                if (selectedKeywords.length > 0){
                        
                    let inAll = true;
                    
                    let objectHandled = false;
                    selectedKeywords.forEach(association =>{
                        if (association.Schlagwort_ID != lastKeywordId){
                            lastKeywordId = association.Schlagwort_ID;
                                
                                objectHandled = false;

                                if(inAll === false){
                                    objectHandled = true;
                                }
                                
                                if(!Object.values(association).includes(vid.id)){
                                    inAll = false;
                                } else {
                                    objectHandled = true;
                                }
                        } else {
                            if (Object.values(association).includes(vid.id) && !objectHandled) {
                                inAll = true;
                                objectHandled = true;
                            }
                            else if(!Object.values(association).includes(vid.id) && !objectHandled){
                                inAll = false;
                            } 
                        }
                    })
                    if (inAll){
                        contentContainer.appendChild(vid);
                        vid.addEventListener('click', function() {
                        openMedium(vid.src, vid.alt || 'Kein Titel', vid.id);
                        initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                    });
                    }
                } else {
                    contentContainer.appendChild(vid);
                    vid.addEventListener('click', function() {
                        openMedium(vid.src, vid.alt || 'Kein Titel', vid.id);
                        initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                    });
                }
            });
        })
        .catch(error => console.error('Fehler beim Laden der Videos:', error));
}

async function loadEbooks() {
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;
    const searchParameter = document.getElementById('searchBar').value;
    const selectedKeywords = await getSelectedKeywordsWithAssociation();

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
            searchParameter: searchParameter,
        })
    })
        .then(response => response.json())
        .then(data => {
            const contentContainer = document.getElementById('contentArea');
            contentContainer.innerHTML = '';
            const ebooks = data.data['Ebooks'];

            ebooks.forEach(ebook => {
                const ebk = document.createElement('img');
                let lastKeywordId = 0;
                ebk.src = '/Mediendatenbank/public/placeholders/placeholder_ebook.png';
                ebk.alt = ebook.Titel;
                ebk.title = ebook.Titel;
                ebk.id = ebook.ebook_ID;
                if (selectedKeywords.length > 0){
                        
                    let inAll = true;
                    
                    let objectHandled = false;
                    selectedKeywords.forEach(association =>{
                        if (association.Schlagwort_ID != lastKeywordId){
                            lastKeywordId = association.Schlagwort_ID;
                                
                                objectHandled = false;

                                if(inAll === false){
                                    objectHandled = true;
                                }
                                
                                if(!Object.values(association).includes(ebk.id)){
                                    inAll = false;
                                } else {
                                    objectHandled = true;
                                }
                        } else {
                            if (Object.values(association).includes(ebk.id) && !objectHandled) {
                                inAll = true;
                                objectHandled = true;
                            }
                            else if(!Object.values(association).includes(ebk.id) && !objectHandled){
                                inAll = false;
                            } 
                        }
                    })
                    if (inAll){
                        contentContainer.appendChild(ebk);
                        ebk.addEventListener('click', function() {
                        openMedium(ebk.src, ebk.alt || 'Kein Titel', ebk.id);
                        initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                    });
                    }
                } else {
                    contentContainer.appendChild(ebk);
                    ebk.addEventListener('click', function() {
                        openMedium(ebk.src, ebk.alt || 'Kein Titel', ebk.id);
                        initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                    });
                }
            });
        })
        .catch(error => console.error('Fehler beim Laden der E-Books:', error));
}

async function loadAudioBooks() {
    const sortingParameter = getSorting().parameter;
    const sortingOrder = getSorting().order;
    const searchParameter = document.getElementById('searchBar').value;
    const selectedKeywords = await getSelectedKeywordsWithAssociation();

    fetch('http://localhost/Mediendatenbank/public/MediumController/getAllMediums', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sortingParameter: sortingParameter,
            direction: sortingOrder,
            searchParameter: searchParameter,
        })
    })
        .then(response => response.json())
        .then(data => {
            const contentContainer = document.getElementById('contentArea');
            contentContainer.innerHTML = '';
            const audiobooks = data.data['Hörbücher'];

            audiobooks.forEach(audiobook => {
                const abk = document.createElement('img');
                let lastKeywordId = 0;
                abk.src = '/Mediendatenbank/public/placeholders/placeholder_audiobook.png';
                abk.alt = audiobook.Titel;
                abk.title = audiobook.Titel;
                abk.id = audiobook.Hörbuch_ID;
                if (selectedKeywords.length > 0){
                        
                    let inAll = true;
                    
                    let objectHandled = false;
                    selectedKeywords.forEach(association =>{
                        if (association.Schlagwort_ID != lastKeywordId){
                            lastKeywordId = association.Schlagwort_ID;
                                
                                objectHandled = false;

                                if(inAll === false){
                                    objectHandled = true;
                                }
                                
                                if(!Object.values(association).includes(abk.id)){
                                    inAll = false;
                                } else {
                                    objectHandled = true;
                                }
                        } else {
                            if (Object.values(association).includes(abk.id) && !objectHandled) {
                                inAll = true;
                                objectHandled = true;
                            }
                            else if(!Object.values(association).includes(abk.id) && !objectHandled){
                                inAll = false;
                            } 
                        }
                    })
                    if (inAll){
                        contentContainer.appendChild(abk);
                        abk.addEventListener('click', function() {
                            openMedium(abk.src, abk.alt || 'Kein Titel', abk.id);
                        initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                    });
                    }
                } else {
                    contentArea.appendChild(abk);
                    abk.addEventListener('click', function() {
                        openMedium(abk.src, abk.alt || 'Kein Titel', abk.id);
                        initModal('modifyMediumModal', 'open-modifyMedium-modal', 'close-modifyMedium-modal');
                    });
                }
            });
        })
        .catch(error => console.error('Fehler beim Laden der Hörbücher:', error));
}

async function loadKeyWords(keyWordElement, listType, deletionButton){
    await fetch('http://localhost/Mediendatenbank/public/KeywordController/getAllKeywordsAndAssociations', {
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
                    keyWords.forEach(async keyword => {
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'keywords[]';
                        checkbox.value = keyword.Schlagwort_ID;
                        checkbox.id = keyword.Schlagwort_ID;

                        if (keyWordElement === 'mediumKeywords'){
                            const mediumId = document.getElementById('modalMedium').value;
                            const checked = await isChecked(mediumId, keyword.Schlagwort_ID);
                            checkbox.onchange = function(){
                                handleCheckboxChange(this, mediumId);
                            }
                            checkbox.checked = checked;
                            
                        }

                        if (keyWordElement === 'keyWordList'){
                            checkbox.onchange = function(){
                                loadAll();
                            }
                        }
        
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

function handleCheckboxChange(checkbox, mediumId){
    if (checkbox.checked){
        fetch('http://localhost/Mediendatenbank/public/KeywordController/createAssociation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                keywordId: checkbox.id,
                mediumId: mediumId,
            })
        })
        .catch(error => console.error('Fehler beim Anlegen des Schlagwortbindings:', error));
    }else{
        console.log("Checkbox " + checkbox.id + " wurde für " + mediumId + " abgewählt.");
        fetch('http://localhost/Mediendatenbank/public/KeywordController/deleteAssociation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                keywordId: checkbox.id,
                mediumId: mediumId,
            })
        })
        .catch(error => console.error('Fehler beim Anlegen des Schlagwortbindings:', error));
    }
}

async function getAssociation(mediumId){
    let associations = [];
    await fetch('http://localhost/Mediendatenbank/public/KeywordController/getKeywordsForSentMedia', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            mediumId: mediumId,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            associations = data.data;
        } else {
            alert('Fehler beim Auslesen des Schlagwortbindings: ' + data.message);
        }
    })
    .catch(error => console.error('Fehler beim Abfragen des Schlagwortbindings:', error));

    return associations;
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
    const users = await getAllUsers();
    const userCount = users.length;
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
            const header = table.createTHead();
            const headerRow = header.insertRow(0);
            const headers = ['User', 'Fotos', 'Videos', 'Ebooks', 'Hörbücher', 'Schlagwörter'];
            headers.forEach((type) => {
                const cell = document.createElement('th');
                cell.textContent = type;
                headerRow.appendChild(cell);
            });

            const tbody = table.createTBody();
            const valueRow = tbody.insertRow();
            const counts = [userCount, photoCount, videoCount, ebookCount, audiobookCount, allKeyWordsCount];
            counts.forEach(count => {
                const cell = valueRow.insertCell();
                cell.textContent = count;
            });

            dbstatsarea.innerHTML = '';
            dbstatsarea.appendChild(table);

            const userTable = document.createElement('table');
            const userHeader = userTable.createTHead();
            const userHeaderRow = userHeader.insertRow(0);

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

            userstatsarea.innerHTML = '';
            userstatsarea.appendChild(userTable);
        })
        .catch(error => {
            console.error('Ein Fehler ist aufgetreten:', error);
        });
}
