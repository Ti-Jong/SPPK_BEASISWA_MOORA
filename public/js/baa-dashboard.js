function cariData(q) {
    var kw = q.toLowerCase().trim();
    var rows = document.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    for (var i = 0; i < rows.length; i++) {
        var ds = rows[i].getAttribute('data-search');
        if (ds !== null) {
            rows[i].style.display = (!kw || ds.includes(kw)) ? '' : 'none';
        }
    }
}
function toggleNotif() {
    var list = document.getElementById('notifList');
    var chevron = document.getElementById('notifChevron');
    if (list) list.classList.toggle('open');
    if (chevron) chevron.classList.toggle('open');
}
