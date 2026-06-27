document.addEventListener('DOMContentLoaded', function() {
    // ==================== GESTION DE LA VISIBILITÉ DU MOT DE PASSE ====================
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('login-password');
    const toggleIcon = document.getElementById('toggle-icon');

    if (togglePassword && passwordInput && toggleIcon) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'password') {
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });
    }

    // ==================== LOGIQUE DU MODE PLEIN ÉCRAN (MANUEL & PERSISTANT) ====================
    const btnFullscreen = document.getElementById('btn-fullscreen');
    if (btnFullscreen) {
        const fsIcon = btnFullscreen.querySelector('i');
        
        // Fonction pour activer ou désactiver le plein écran
        btnFullscreen.addEventListener('click', function(e) {
            e.preventDefault(); // Empêche le rechargement ou la navigation
            
            // Vérification de l'état actuel du plein écran
            const isFullscreen = document.fullscreenElement || 
                                 document.webkitFullscreenElement || 
                                 document.mozFullScreenElement || 
                                 document.msFullscreenElement;
            
            if (!isFullscreen) {
                // Demande d'activation du plein écran sur tout le document
                const docEl = document.documentElement;
                if (docEl.requestFullscreen) {
                    docEl.requestFullscreen();
                } else if (docEl.webkitRequestFullscreen) {
                    docEl.webkitRequestFullscreen();
                } else if (docEl.mozRequestFullScreen) {
                    docEl.mozRequestFullScreen();
                } else if (docEl.msRequestFullscreen) {
                    docEl.msRequestFullscreen();
                }
            } else {
                // Demande de sortie du plein écran
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        });

        // Met à jour dynamiquement le bouton et l'icône de la barre de navigation
        const updateFullscreenUI = () => {
            const isFullscreen = document.fullscreenElement || 
                                 document.webkitFullscreenElement || 
                                 document.mozFullScreenElement || 
                                 document.msFullscreenElement;
            if (isFullscreen) {
                // Mode plein écran actif : on propose de réduire
                fsIcon.className = "bi bi-fullscreen-exit";
                btnFullscreen.style.backgroundColor = "#e74c3c"; // Rouge pour indiquer une action de sortie
                btnFullscreen.style.color = "#ffffff";
            } else {
                // Mode normal actif : on propose d'agrandir
                fsIcon.className = "bi bi-fullscreen";
                btnFullscreen.style.backgroundColor = "#2c3e50"; // Couleur d'origine
                btnFullscreen.style.color = "#f1c40f";
            }
        };

        // Écouteurs d'événements pour détecter si l'utilisateur quitte le plein écran (ex: touche Échap)
        document.addEventListener('fullscreenchange', updateFullscreenUI);
        document.addEventListener('webkitfullscreenchange', updateFullscreenUI);
        document.addEventListener('mozfullscreenchange', updateFullscreenUI);
        document.addEventListener('MSFullscreenChange', updateFullscreenUI);
    }
});