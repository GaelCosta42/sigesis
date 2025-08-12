var markers;
var address;
var lat;
var lng;
var sleep = 1;
var count = 0;
function initMapCep(cep,num,elm)
{
    address = getAddr(cep,num);
    GMaps.geocode({
        address: address,
        callback: function(results, status) {            
            if (status == 'OK') {
                var latlng = results[0].geometry.location;
                lat = latlng.lat();
                lng = latlng.lng()
                map = new GMaps({
                    div: elm,
                    lat: lat,
                    lng: lng,
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    streetViewControl: true,
                    zoom: 13
                })
                map.setCenter(lat, lng);
            }
        }
    });       
}
function initMap(address,elm)
{
    GMaps.geocode({
        address: address,
        callback: function(results, status) {            
            if (status == 'OK') {
                var latlng = results[0].geometry.location;
                lat = latlng.lat();
                lng = latlng.lng()
                map = new GMaps({
                    div: elm,
                    lat: lat,
                    lng: lng,
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    streetViewControl: true,
                    zoom: 16
                })
                map.setCenter(lat, lng);
            }
        }
    });       
}

function addMarker(address,html)
{
	if(sleep >= 7){
		sleep = 1;
	}
	GMaps.geocode({
		address: address,
		callback: function(results, status) {            
			if (status == 'OK') {
				var latlng = results[0].geometry.location;
				if(html == ""){
					html = address;
				}
				lat = latlng.lat();
				lng = latlng.lng();
				var icon = "assets/img/marker.png";
				map.addMarker({
					lat: lat,
					lng: lng,
					icon: icon,
					title: address,
					infoWindow: {
						content: html
					}
				}); 
				count++
			}
			else{
				sleep++;
				//console.log( sleep + ' * 200 -> ' + sleep * 200)
				setTimeout(function(){
					addMarker(address,html)
				}, sleep * 200)	
			}
		}
	})
}

function addMarkerCep(cep,num,html)
{
	if(sleep >= 10 ){
		sleep = 1;
	}	
    address = getAddr(cep,num);
    GMaps.geocode({
        address: address,
        callback: function(results, status) {            
            if (status == 'OK') {
                var latlng = results[0].geometry.location;
                if(html == ""){
                    html = address;
                }
                lat = latlng.lat();
                lng = latlng.lng();
                 var icon = "assets/img/marker.png";
                map.addMarker({
                    lat: lat,
                    lng: lng,
                    icon: icon,
                    //title: address,
                    infoWindow: {
                        content: html
                    }
                }); 
            }
			else{
				sleep++;
				setTimeout(function(){
					addMarkerCep(cep,num,html)
				}, sleep * 200)	
			}			
        }
    })    
}
function getAddr(cep,num) {
    var url = 'http://xtends.com.br/webservices/cep/json/'+cep+'/';    
    if ($.browser.msie) {
        var url = 'ie.php';    
    }    
    rs = $.parseJSON($.ajax({
        url:url,
        cep:cep,
        async: false
    }).responseText);
    return  rs.logradouro + ', ' + num + ', ' + rs.bairro + ', ' + rs.cidade + ', ' + rs.uf;    
}
