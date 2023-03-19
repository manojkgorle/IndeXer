var url = new URL('https://www.mk.subdoain.example.com/folders/access=?var=xyz');
if (url.hostname.startsWith('www.')) {
    url.hostname = url.hostname.slice(4);
}
const hostnameParts = url.hostname.split('.');
console.log(url);
console.log(hostnameParts);
console.log(hostnameParts.slice(0,-2));
console.log(hostnameParts[0]);
if (hostnameParts.length > 2) {
    const subdomain = hostnameParts.slice(0, -2).join('.');
    console.log(subdomain); // 'subdomain'
    console.log(hostnameParts.slice(hostnameParts.length -2).join('.'));
} else {
    console.log('No subdomain found');
}
console.log(url.hostname);
const ver = 'hello';
console.log(ver + 'manoj');