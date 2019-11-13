let banner = document.getElementById("banner")
let bannerText = document.getElementById("bannerText")
let logoBde = document.getElementById("logoBde")
window.scrollTo(0, 0)
document.addEventListener('wheel', function(event){
    if(event.deltaY<0 && banner.style.height=="200px" && window.scrollY==0){
        banner.style.height="560px"
        document.getElementById("nav").style.top="560px"
        bannerText.style.fontSize="1em"
        logoBde.style.width="180px"
        banner.style.backgroundSize="2880px"
    } else if(event.deltaY>0 ||(event.deltaY<0 && window.scrollY>=102)){
        banner.style.height="200px"
        document.getElementById("nav").style.top="200px"
        bannerText.style.fontSize="0.5em"
        logoBde.style.width="80px"
        banner.style.backgroundSize="2112px"
    }
})