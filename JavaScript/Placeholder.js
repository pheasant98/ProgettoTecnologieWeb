window.onload = menu;

function menu() {
    document.getElementById("hamburgerMenu").addEventListener("click", touch); // ascoltatore
    document.getElementById("userTools").addEventListener("click", touch2);
    // document.getElementById("skipMenu").addEventListener("click", touch)

    function touch() { //classe gestione ascoltatore
        let menus;
        let content;
        let breadcrumbs;

        menus = document.getElementById("menu"); // prende l'id dell'elemento cliccato
        menus.classList.toggle("show"); // inserisci dentro al tag con l'id cliccato, la nuova classe

        content = document.getElementById("content");
        content.classList.toggle("hide");

        breadcrumbs = document.getElementById("breadcrumbs");
        breadcrumbs.classList.toggle("hide");
    }

    function touch2() {
        let user = document.getElementById("userMenu");
        user.classList.toggle("show");
    }
}
