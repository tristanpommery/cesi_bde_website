let productImage = document.getElementById('product_image')
let dragDropText = document.getElementById('dragDropText')

productImage.addEventListener('dragenter', ()=>{
    dragDropText.innerHTML='<i class="fas fa-file-import"></i>'
    dragDropText.style.fontSize="4em"
})

productImage.addEventListener('dragleave', ()=>{
    dragDropText.innerHTML='Glissez-déposez ici'
    dragDropText.style.fontSize="1.5em"
})

productImage.addEventListener('drop', ()=>{
    dragDropText.innerHTML='<i class="far fa-file-image"></i>'
    dragDropText.style.fontSize="4em"
})