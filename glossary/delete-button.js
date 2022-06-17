const deleteWord =(id)=>{

        fetch('delete.php',{
            method: 'POST',
            body:JSON.stringify({id: id})
        }).then(response => {
            response.text();
            window.location.reload();
        }).then(console.log)
}

