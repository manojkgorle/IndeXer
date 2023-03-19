//search box -- remove text in field when clicked outside the search window
var searchBox = document.querySelectorAll('.search-box input[type="text"] + span');
    searchBox.forEach((elm) => {elm.addEventListener('click', () => {elm.previousElementSibling.value = '';});});
//ajax function for onload folder content
var sendparam_folder = sendparam_tag = sendparam_date = sendparam_domain = 0;
function httpfolder(){
    var xmlhttpfolder = new XMLHttpRequest();
    xmlhttpfolder.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("folder").innerHTML = this.responseText;
            sendparam_folder += 1;
        }
    }
    xmlhttpfolder.open("POST","ajax/folder.php",true);
    xmlhttpfolder.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttpfolder.send("sendparam="+sendparam_folder);
    //query string name sendparam -- where initial pass by values will be sent starting from zero with increment -- each increment includes get 25
}
function httptag(){
    //ajax function for onload tag content
    var xmlhttptag = new XMLHttpRequest();
    xmlhttptag.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("tag").innerHTML = this.responseText;
            sendparam_tag += 1;
        }
    }
    xmlhttptag.open("POST","ajax/tag.php",true);
    xmlhttptag.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttptag.send("sendparam="+sendparam_tag);//make it proper
}
function httpdate(){
    //ajax function for onload date content
    var xmlhttpdate = new XMLHttpRequest();
    xmlhttpdate.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("date").innerHTML = this.responseText;
            sendparam_date += 1;
        }
    }
    xmlhttpdate.open("POST","ajax/date.php",true);
    xmlhttpdate.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttpdate.send("sendparam="+sendparam_date);//make it proper
}
function httpdomain(){
    //ajax function for onload domain content
    var xmlhttpdomain = new XMLHttpRequest();
    xmlhttpdomain.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("domain").innerHTML = this.responseText;
            sendparam_domain += 1;
        }
    }
    xmlhttpdomain.open("POST","ajax/domain.php",true);
    xmlhttpdomain.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttpdomain.send("sendparam="+sendparam_domain);//make it proper
} 
function redirectfolder(a,b){
    window.location.href = "folder?folder_id="+b;
}
window.onload = function(){
    httpfolder();
    httptag();
    httpdate();
    httpdomain();
}
