//extracting domain with & with out subdomains
function updatedomain() {
    console.log("shh");
    const url = new URL(document.getElementById('url-js').value);
    add_options = "";
    if(url.hostname.startsWith('www.')){
        url.hostname = url.hostname.slice(4);
    }
    const hostnameParts = url.hostname.split('.');
    if(hostnameParts.length > 2){
       let subdomain = url.hostname;;
        let domain = hostnameParts.slice(hostnameParts.length - 2).join('.');
        add_options = "<option value='"+subdomain+"'>"+subdomain+"</option><option value='"+domain+"'>"+domain+"</option>";
    }else{
        let domain = url.hostname;
        add_options = "<option value='"+domain+"'>"+domain+"</option>";
    }
    document.getElementById('domain-js').innerHTML = add_options;
}