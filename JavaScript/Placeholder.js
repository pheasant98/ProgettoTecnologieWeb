window.onload = menu;

function touch() {
    let menus;
    let content;
    let breadcrumbs;

    menus = document.getElementById("menu");
    menus.classList.toggle("show");

    content = document.getElementById("content");
    content.classList.toggle("hide");

    breadcrumbs = document.getElementById("breadcrumbs");
    breadcrumbs.classList.toggle("hide");
}

function menu() {
    document.getElementById("hamburgerMenu").addEventListener("click", touch);
}
