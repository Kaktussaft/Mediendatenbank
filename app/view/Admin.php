<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <script type="text/javascript" src="/Mediendatenbank/app/view/script/SharedScripts.js"></script>
    <link rel="stylesheet" href="/Mediendatenbank/app/View/style/PopUps.css">
    <link rel="stylesheet" href="/Mediendatenbank/app/View/style/AdminPage.css">
</head>

<body>
    <div class="view">
        <img src="/Mediendatenbank/Design/rakete.png" alt="" width="100px" height="100px">
        <span>ADMIN - <br>Space</span>
    </div>   
    
    <hr>

    <div class="toggleViewAndLogout">
        <a href="#" id="toggle-user-view">Userview</a>
        <a href="#" id="logout">Logout</a>
    </div>
    
    <div class="navigationPanel">
        <button id="navDashboard">Dashboard</button>
        <button id="open-accountModification-modal">Nutzerverwaltung</button>
    </div>
    <div class="contentArea">
        <h2>Datenbank-Statistik</h2><br>
        <div id="dbStats"></div>
        <br>
        <h2>User-Statistik</h2><br>
        <div id="userStats"></div>
    </div>

    <div id="accountModificationModal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-accountModification-modal">&times;</span>
            <h2>Konto bearbeiten</h2>
            <div id="accountModificationInfos">
                <select name="accountSelection" id="accountSelection"></select><br>
                <label for="username">Username:</label>
                <input type="text" id="name" name="name" required>
                <br>
                <label for="email">E-Mail:</label>
                <input type="text" id="email" name="email" required>
                <br>
                <label for="lastname">Nachname:</label>
                <input type="text" id="lastname" name="lastname" required>
                <br>
                <label for="firstname">Vorname:</label>
                <input type="text" id="firstname" name="firstname" required>
                <br>
                <label for="isAdmin">Admin:</label>
                <input type="radio" name="isAdmin" id="isAdminTrue" value="admin">ja
                <input type="radio" name="isAdmin" id="isAdminFalse" value="false" checked>nein
                <br><br>
                <button id="modifyUserButton">Abschicken</button>
                <button id="deleteUserButton">Nutzer löschen</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initModal('accountModificationModal', 'open-accountModification-modal', 'close-accountModification-modal');

            document.getElementById('toggle-user-view').addEventListener('click', function(event) {
                event.preventDefault();
                history.replaceState(null, '', '/Mediendatenbank/public/UserController/toggleUserView/');
                window.location.reload();
            });
            document.getElementById('logout').addEventListener('click', function(event) {
                event.preventDefault();
                history.replaceState(null, '', '/Mediendatenbank/public/UserController/logout/');
                window.location.reload();
            });

            document.getElementById('navDashboard').addEventListener('click', function(event){
<<<<<<< HEAD
                await loadDashboard();
=======
                loadDashboard();
>>>>>>> 9e3a3bc073a436082c68f4401ea2f32067b5b820
            });

            document.getElementById('modifyUserButton').addEventListener('click', function(event) {
                updateUserAdmin();
            });

            document.getElementById('deleteUserButton').addEventListener('click', function(event) {
                deleteUser();
            });

            loadUsers('accountSelection');
        });
    </script>
</body>

</html>