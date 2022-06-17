const EditWord=(id)=>{
    document.cookie = `id=${id}`;
    window.open("edit.php", '_self');
}