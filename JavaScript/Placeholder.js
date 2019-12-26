window.onload = menu;
function menu()
{
    document.getElementById("HamburgerMenu").addEventListener("click",touch);//ascoltatore

    function touch()//classe gestione ascoltatore
    {
        var Hmenu=document.getElementById("menu");//prende l'id dell'elemento cliccato
        Hmenu.classList.toggle("show"); //scambia l'id con la nuova classe
    }

}