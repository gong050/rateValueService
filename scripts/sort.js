function change(){
    var button = document.getElementById("click");
    if(button.innerHTML == '↑'){
        button.innerHTML = '↓'
    } else {
        button.innerHTML = '↑'
    }
}

var options = {
    valueNames: ['name', 'charcode', 'nominal', 'value', 'date'],
    page: 25,
    pagination: true
}

var userList = new List('rate', options);