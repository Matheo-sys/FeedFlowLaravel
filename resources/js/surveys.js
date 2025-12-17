window.copyLink = function(url, button) {
    // Fonction interne pour déclencher le feedback visuel
    const triggerFeedback = () => {
        let originalContent = button.innerHTML;
        button.innerText = 'Copié !';
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('bg-green-600');
        }, 2000);
    };

    // Méthode 1 : API Clipboard moderne (nécessite HTTPS ou localhost)
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url)
            .then(triggerFeedback)
            .catch(err => {
                console.warn('Echec Clipboard API, tentative fallback...', err);
                fallbackCopy(url, button, triggerFeedback);
            });
    } else {
        // Méthode 2 : Fallback pour HTTP ou anciens navigateurs
        fallbackCopy(url, button, triggerFeedback);
    }
};

function fallbackCopy(url, button, onSuccess) {
    const textArea = document.createElement("textarea");
    textArea.value = url;
    
    // Position hors écran pour ne pas gêner l'utilisateur
    textArea.style.position = "fixed";
    textArea.style.left = "-9999px";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful && onSuccess) onSuccess();
    } catch (err) {
        console.error('Erreur copie :', err);
        prompt('Impossible de copier automatiquement. Copiez ce lien :', url);
    }
    
    document.body.removeChild(textArea);
}
