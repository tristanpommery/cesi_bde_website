let banner = document.getElementById("banner")
let bannerText = document.getElementById("bannerText")

document.addEventListener('scroll', () => {
    if(window.scrollY>0){
        if(banner.style.height=="560px"){window.scrollTo(0, 1)}
        banner.style.height="200px"
        document.getElementById("nav").style.top="200px"
        bannerText.style.fontSize="0.5em"
        banner.style.backgroundSize="110%"
    } else if(window.scrollY==0){
        banner.style.height="560px"
        document.getElementById("nav").style.top="560px"
        bannerText.style.fontSize="1em"
        banner.style.backgroundSize="150%"
    }
})

//document.addEventListener('click', () => console.log(window.scrollY))