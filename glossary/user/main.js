const description=document.getElementById("vysvetlenie")
const translation=document.getElementById("preklad")

description.addEventListener('change',()=>{
    if(description.checked){
        console.log(123)
        document.getElementById("vBox").style.display="block";
        document.getElementById("pBox").style.display="none";
    }
})
translation.addEventListener('change',()=>{
    if(translation.checked){
        document.getElementById("vBox").style.display="none";
        document.getElementById("pBox").style.display="block";
    }
})
