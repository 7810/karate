//Permet de ne pas dépasser les 300 caractères pour les champs de type textarea ou 1500 pour celui des messages

var maxLength = {
    default: 300,
    mymessage: 1500
};

var textarea = document.querySelector('textarea');

textarea.addEventListener('input', function() {
    var id = this.id || 'default';
    var limit = maxLength[id] || maxLength['default'];

    if (textarea.value.length > limit) {
        textarea.value = textarea.value.substring(0, limit);
    }
});
