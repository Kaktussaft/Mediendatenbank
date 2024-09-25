<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMediaSpace</title>
    <script type="text/javascript" src="/Mediendatenbank/app/View/script/SharedScripts.js"></script>
    <link rel="stylesheet" href="/Mediendatenbank/app/View/style/LandingPage.css">
    <link rel="stylesheet" href="/Mediendatenbank/app/View/style/popUps.css">    

</head>

<body>

    <div class="welcomeMessage">
        <h1>Nutze den unendlichen Raum für deine Medien!</h1>
    </div>

    <div class="loginForm">
        <form id="loginForm" action="UserController/login/" method="POST" onsubmit="updateFormLoginAction()">
            <div class="userTextBox">
                <input type="text" id="username" name="username" placeholder="Username" required><br>
            </div>
            <input type="submit" value="Login" class="loginButton">
            <input type="hidden" name="Routing" value="login">
        </form>
        <div class="registrationMessage">
            <p>Noch keinen Login?<br>Registriere dich <a href="#" id="open-registration-modal">hier.</a></p>
        </div>
    </div>

    <div id="registrationModal" class="modal">
        <div class="modal-content_LP">
            <span class="close" id="close-registration-modal">&times;</span>
            <h2>Registrierung</h2>
            <form id="registrationForm" action="" method="POST" onsubmit="submitRegistration()">
                <label for="firstname">Vorname:</label>
                <input type="text" id="name" name="firstname" required>
                <br>
                <label for="lastname">Nachname:</label>
                <input type="text" id="surname" name="lastname" required>
                <br>
                <label for="username">Username:</label>
                <input type="text" id="username_registration" name="name" required>
                <br>
                <label for="email">E-Mail:</label>
                <input type="text" id="email" name="email" required>
                <br>
                <button type="submit">Registrieren</button>
            </form>
        </div>
    </div>

    <script>
       function updateFormLoginAction() {
            var username = document.getElementById('username').value;
            var form = document.getElementById('loginForm');
            form.action = 'UserController/login/' + username;
            console.log(username);
            return true; 
        }

        function submitRegistration() {
            var name = document.getElementById('name').value;
            var surname = document.getElementById('surname').value;
            var username = document.getElementById('username_registration').value;
            var email = document.getElementById('email').value;
            var form = document.getElementById('registrationForm');
            form.action = 'UserController/register/' + name + '/' + surname + '/' + username + '/' + email;
            return true; 
        }

        document.addEventListener('DOMContentLoaded', function() {
            initModal('registrationModal', 'open-registration-modal', 'close-registration-modal');
        });
    </script>
</body>

</html>