let productImage = document.getElementById('registration_form_image')
let dragDropText = document.getElementById('dragDropText')
let terms = document.getElementById('termsCheckbox')
let checked = document.getElementById('checked')
let notChecked = document.getElementById('notChecked')

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

terms.addEventListener( 'change', function() {
    if(this.checked) {
        checked.style.display="block"
        notChecked.style.display="none"
    } else {
        checked.style.display="none"
        notChecked.style.display="block"
    }
});