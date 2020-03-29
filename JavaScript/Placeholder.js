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

function touchSecond() {
    let user = document.getElementById("userMenu");
    user.classList.toggle("show");
}

function menu() {
    document.getElementById("hamburgerMenu").addEventListener("click", touch);
    document.getElementById("userTools").addEventListener("click", touchSecond);
    // document.getElementById("skipMenu").addEventListener("click", touch)
}
