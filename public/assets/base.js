let sideMenu = document.getElementById("sideMenu")
let menuIcon = document.getElementById("menuIcon")
let menuWidth = sideMenu.scrollWidth
menuIcon.addEventListener("click", animateMenu)

function animateMenu(){
    if(sideMenu.style.left=="0px"){
        menuIcon.className="fas fa-bars"
        sideMenu.style.left=`-${menuWidth}px`
    } else {
        menuIcon.className="fas fa-times"
        sideMenu.style.left = "0px"
    }
}

document.addEventListener("scroll", function(){
    if(sideMenu.style.left=="0px"){
        animateMenu()
    }
})