window.onload = menu;
function menu()
{
    document.getElementById("HamburgerMenu").addEventListener("click",touch);//ascoltatore
    document.getElementById("UserTools").addEventListener("click", touch2);

    function touch()//classe gestione ascoltatore
    {
        var Hmenu=document.getElementById("menu");//prende l'id dell'elemento cliccato
        Hmenu.classList.toggle("show"); //inserisci dentro al tag con l'id cliccato, la nuova classe
        var Content=document.getElementById("content");
        Content.classList.toggle("hide");
        var Breadcrumbs=document.getElementById("breadcrumbs");
        Breadcrumbs.classList.toggle("hide");
    }

    function touch2() {
        var UserM=document.getElementById("UserMenu");
        UserM.classList.toggle("show");
    }

}