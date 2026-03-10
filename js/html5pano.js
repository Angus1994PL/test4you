(function ( $ ) {
    
    'use strict';
 
    $.fn.html5pano = function( options ) {
        
        var scene;
        var sphere;
        var sphereMaterial;
		var sphereTexture;
        var loader;
        var video;
        var mov_mode = false;
		//var lowSpec=false;
		
        var autoRotate = false;
        var panoDiv;
        var panosXML;
        var edycja = false;
        var NO_view = false;
        var offline = false;
        var pomiar = false;
        var pomiar_punkty = [];
        var pomiar_plane;
        var pointToAddName;
        var pointToAdd;
        var pointToAddID;
        var adding;
        var wybMin;
        var delMode = false;
        var panoID;
        var ID;
        var tooltip;
        var delBT; //zeby zmienic oznaczenie z zewnatrz
        var xmlLink;
        var logoLink = '';
        var nazwaBiura = '';
        var Slon = 0;
        var Slat = 0;
        var miniatury = [];

        var edW; //okno edycji
        //var edWin;
        var edWmin;
		var edWi; //okno edycji info
        var edWiIn;
        var edWitxt;
        var mierzW;

        //obiekty do znajdywania klikniec w 3d
        var raycaster;
        var mouse;

        var punkty = [];

        var manualControl = false;
        var tochMove = false;
		var longitude = 0;
        var latitude = 0;
        var savedX;
        var savedY;
        var savedLongitude;
        var savedLatitude;
        var ruchX = 0;
        var ruchY = 0;

        var sizeXold; //pierwotny rozmiar div z panorama
        var sizeYold;

        var fov = 75;
        var camera;
        var renderer;
        var imgkatalog = '/img/panoskin';
        var tytul;
        
 
        /*
         * USTAWIENIA DOMYŚLNE PANORAMY
         */
        var settings = $.extend({
            'typ': 'pano',
            'xml': '',
            'IDxml': '',
            'tryb': '',
            'link': '',
			'lowSpec':false
        }, options);
        
        // ----------------------------------------------------------------------------------
        
        
        /**
         * 
         * @returns {undefined}
         */
        function render() {
           
			requestAnimationFrame(render);
            if (ruchX != 0)
                if(!tochMove){
					longitude += ruchX;
					}else{
					//longitude += ruchX;	
					}
            if (ruchY != 0)
				if(!tochMove){
					latitude += ruchY;
					}else{
					//latitude += ruchY;
					}
            if (autoRotate)
                longitude += 0.4;
            if (!manualControl) {
                //longitude += 0.1;
                if(Math.abs(ruchX)>0.01){
					ruchX=ruchX*0.95;}
				else{
					ruchX = 0;}
               
			    if(Math.abs(ruchY)>0.01){
					ruchY=ruchY*0.95;}
				else{
					ruchY = 0;}
            }
            //videoTexture.needsUpdate = true;
            // ograniczenie konta patrzenia -85 to 85 
            latitude = Math.max(-85, Math.min(85, latitude));

            // ruch kamery
            camera.target.x = 50 * Math.sin(THREE.Math.degToRad(90 - latitude)) * Math.cos(THREE.Math.degToRad(longitude));
            camera.target.y = 50 * Math.cos(THREE.Math.degToRad(90 - latitude));
            camera.target.z = 50 * Math.sin(THREE.Math.degToRad(90 - latitude)) * Math.sin(THREE.Math.degToRad(longitude));
            if (pomiar) {
                camera.position.set(0, 0, 0);
            } else {
               camera.position.copy(camera.target).negate()*0.1;
            }
            camera.lookAt(camera.target);
            //camera.fov=fov;
            renderer.render(scene, camera);
        }
        
        
        /**
         * 
         * @param {type} idpano
         * @param {type} link
         * @returns {undefined}
         */
        function init_mov(idpano, link) {
            var WebFontConfig = {
                google: {
                    families: ['Khand::latin']
                }
            };
            (function () {
                var wf = document.createElement('script');
                wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
                wf.type = 'text/javascript';
                wf.async = 'true';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(wf, s);
            })();

            var pano_div = document.getElementById(idpano);
            sizeXold = pano_div.offsetWidth;
            sizeYold = pano_div.offsetHeight;
            panoDiv = pano_div;
            renderer = new THREE.WebGLRenderer({
                antialias: true
            });
            renderer.setSize(pano_div.offsetWidth, pano_div.offsetHeight);
            pano_div.appendChild(renderer.domElement);

            tooltip = document.createElement('div');
            tooltip.style.position = 'absolute';
            tooltip.id='ww_tooltip';
			//tooltip.style.fontFamily = 'Khand,sans-serif';
            //tooltip.style.background = 'rgba(0, 0, 0, 0.7)';
            //tooltip.style.color = 'white';
            //tooltip.style.padding = 5 + 'px';
            //tooltip.style.border = '1px solid rgba(22, 22, 22, 1)';
            //tooltip.style.borderRadius = '3px';
            //tooltip.style.boxShadow = "5px 10px 20px #222222";
            tooltip.name = 'tooltip';
            tooltip.style.zIndex = 1000;

            ////tytul sceny
            tytul = document.createElement('div');
            tytul.style.position = 'absolute';
            //tytul.style.fontFamily = 'Khand,sans-serif';
            //tytul.style.fontSize = 20 + 'px';
            //tytul.style.color = 'white';
            //tytul.style.fontWeight = 'bold';
            //tytul.style.textShadow = '1px 1px 15px #000000 ';
            tytul.style.top = 3 + 'px';
            tytul.style.display = 'none';
            tytul.id='ww_title';
			pano_div.appendChild(tytul);

            // definowanie sceny
            scene = new THREE.Scene();

            // kamera
            camera = new THREE.PerspectiveCamera(75, pano_div.offsetWidth / pano_div.offsetHeight, 1, 1000);
            camera.target = new THREE.Vector3(0, 0, 0);

            // geometria
            sphere = new THREE.SphereGeometry(100, 100, 40);
            sphere.applyMatrix(new THREE.Matrix4().makeScale(-1, 1, 1));

            //wektory klikniec myszy
            raycaster = new THREE.Raycaster();
            mouse = new THREE.Vector2();

            ////tworzenie obiektu video
            video = document.createElement('video');
            video.src = link;
            video.load();
            video.play();

            var texture = new THREE.VideoTexture(video);
            texture.minFilter = THREE.LinearFilter;
            texture.magFilter = THREE.LinearFilter;

            var movieMaterial = new THREE.MeshBasicMaterial({
                map: texture
            });



            var sphereMesh = new THREE.Mesh(sphere, movieMaterial);
            //sphereMesh.name = 'sfera';
            scene.add(sphereMesh);

            // materiał z loaderem tworzacy obiekt
            //sphereMaterial = new THREE.MeshBasicMaterial();
            //sphereMaterial.map = THREE.ImageUtils.loadTexture("pano.jpg")

            renderer.domElement.addEventListener("mousedown", onDocumentMouseDown, false);
            renderer.domElement.addEventListener("mousemove", onDocumentMouseMove, false);

            renderer.domElement.addEventListener("touchstart", onDocumentMouseDown, false);
            renderer.domElement.addEventListener("touchmove", onDocumentMouseMove, false);
			renderer.domElement.addEventListener("touchend", onDocumentMouseUp, false);
            //setInterval(onDocumentMouseMove, 100);

            document.addEventListener("mouseup", onDocumentMouseUp, false);
            renderer.domElement.addEventListener("mousewheel", onDocumentMouseWheel, false);
            renderer.domElement.addEventListener("DOMMouseScroll", onDocumentMouseWheel, false);
            renderer.domElement.addEventListener("onmousewheel", onDocumentMouseWheel, false);

            render();

            window.addEventListener('resize', onWindowResize, false);

            function onWindowResize() {
                camera.aspect = pano_div.offsetWidth / pano_div.offsetHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(pano_div.offsetWidth, pano_div.offsetHeight);
                render();
            }
            mov_mode = true;
            buildInterface(pano_div);

        }
        
        
        /**
         * 
         * @param {type} idpano
         * @param {type} xml
         * @param {type} IDxml
         * @param {type} tryb
         * @returns {undefined}
         */
        function init_pano(idpano, xml, IDxml, tryb) {
            if (tryb == 1)
                edycja = true;
            if (tryb == 2)
                NO_view = true;
				settings.lowSpec=true;
            if (tryb == 3) {
                offline = true;
                imgkatalog = "panoskin";
            }
			
			var WebFontConfig = {
                google: {
                    families: ['Khand::latin']
                }
            };
            (function () {
                var wf = document.createElement('script');
                wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
                wf.type = 'text/javascript';
                wf.async = 'true';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(wf, s);
            })();

            var pano_div = document.getElementById(idpano);
			
            sizeXold = pano_div.offsetWidth;
            sizeYold = pano_div.offsetHeight;
            panoDiv = pano_div;
            if(!settings.lowSpec){
				renderer = new THREE.WebGLRenderer({
					antialias: true
				});}else{
				renderer = new THREE.WebGLRenderer({
					antialias: false
				});	
				}
            renderer.setSize(pano_div.offsetWidth, pano_div.offsetHeight);
            pano_div.appendChild(renderer.domElement);
            ID = IDxml;
            edW = document.createElement('div');
            //edWin = document.createElement('center');
            edW.style.color = 'white';
            edW.innerHTML = '<hr id="ww_hr">Wybierz scene docelową<hr id="ww_hr">';
            edW.style.display = 'none';
			edWmin = document.createElement('div');
			//edW.appendChild(edWin);
            //edW.appendChild(edWmin);
			edW.id="ww_min";
			pano_div.appendChild(edW);
			
			
			
            //tooltip
            tooltip = document.createElement('div');
            tooltip.style.position = 'absolute';
            tooltip.id='ww_tooltip';
			tooltip.name = 'tooltip';
            tooltip.style.zIndex = 1000;
            pano_div.appendChild(tooltip);
            
			////tytul sceny
            tytul = document.createElement('div');
            tytul.style.position = 'absolute';
            tytul.style.top = 3 + 'px';
            tytul.style.display = 'none';
            tytul.id='ww_title';
			if(NO_view)tytul.id='ww_title_NO';
			pano_div.appendChild(tytul);

           

            // definowanie sceny
            scene = new THREE.Scene();

            // kamera
            camera = new THREE.PerspectiveCamera(75, pano_div.offsetWidth / pano_div.offsetHeight, 1, 1000);
            camera.target = new THREE.Vector3(0, 0, 0);

            // geometria
            sphere = new THREE.SphereGeometry(100, 100, 40);
            sphere.applyMatrix(new THREE.Matrix4().makeScale(-1, 1, 1));

            //wektory klikniec myszy
            raycaster = new THREE.Raycaster();
            mouse = new THREE.Vector2();

            // materiał z loaderem tworzacy obiekt
			sphereTexture= new THREE.Texture();
            sphereMaterial = new THREE.MeshBasicMaterial();
            //sphereMaterial.map = THREE.ImageUtils.loadTexture("pano.jpg")

            function parseXml(xmlStr) {
                return (new window.DOMParser()).parseFromString(xmlStr, "text/xml");
            }

            //wczytajPano('pano.jpg');
            if (!offline) {
                loadXMLDoc(xml, processXML);
            }
            else {
                alert(document.getElementById("xml").innerHTML);
                processXML(parseXml(document.getElementById("xml").innerHTML));
            }

            xmlLink = xml;
            renderer.domElement.addEventListener("mousedown", onDocumentMouseDown, false);
            renderer.domElement.addEventListener("mousemove", onDocumentMouseMove, false);

			renderer.domElement.addEventListener("touchstart", onDocumentMouseDown, false);
            renderer.domElement.addEventListener("touchmove", onDocumentMouseMove, false);
            renderer.domElement.addEventListener("touchend", onDocumentMouseUp, false);
			//setInterval(onDocumentMouseMove, 100);

            document.addEventListener("mouseup", onDocumentMouseUp, false);
            renderer.domElement.addEventListener("mousewheel", onDocumentMouseWheel, false);
            renderer.domElement.addEventListener("DOMMouseScroll", onDocumentMouseWheel, false);
            renderer.domElement.addEventListener("onmousewheel", onDocumentMouseWheel, false);

            render();

            window.addEventListener('resize', onWindowResize, false);

            function onWindowResize() {
                camera.aspect = pano_div.offsetWidth / pano_div.offsetHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(pano_div.offsetWidth, pano_div.offsetHeight);
                render();
            }
            if (NO_view) {
                buildInterfaceNO(pano_div);
            } else {
                buildInterface(pano_div);
            }
        }
        
        /**
         * 
         * @param {type} xml
         * @returns {undefined}\
         */
        function processXML(xml) {
            panosXML = xml;
            if (xml.getElementsByTagName("logo")[0]) {
                logoLink = xml.getElementsByTagName("logo")[0].attributes.getNamedItem("link").value;
                nazwaBiura = xml.getElementsByTagName("logo")[0].attributes.getNamedItem("nazwa").value;
            }
            
            var x = xml.getElementsByTagName("pano");
            for (var i = 0; i < x.length; i++) {
                if (x[i].attributes.getNamedItem("id").value == ID)
                    wczytajPano(xml.getElementsByTagName("pano")[i].attributes.getNamedItem("link").value);
            }

            if (edycja == true) {
                for (var i = 0; i < x.length; i++) {
                    var nam = x[i].attributes.getNamedItem("nazwa").value;
                    var min = x[i].attributes.getNamedItem("min").value;
                    var link = x[i].attributes.getNamedItem("link").value;
                    var id = x[i].attributes.getNamedItem("id").value;
                    addMin(nam, min, link, id);
                }
				edWmin.innerHTML+='<br><br>';
            }
        }
        
        
        /**
         * 
         * @param {type} xml
         */
        function processXMLEdit(xml) {
            panosXML = xml;
        }
        
        
        /**
         * 
         * @param {type} nazwa
         * @param {type} mlink
         * @param {type} link
         * @param {type} id
         */
        function addMin(nazwa, mlink, link, id) {
            var min = document.createElement('img');
			min.src = mlink;
            min.height = 50;
            min.hspace = '5';
            min.vspace = '5';
            min.style.opacity = '0.7';
            //min.title = nazwa;
            showTooltip2(min, nazwa);
            //min.style.borderRadius = '5px';
            //min.style.paddingLeft=5;
            //min.style.paddingRight=5;
            //min.style.paddingTop=15;
			min.id='ww_min_'+mlink;
			//$("#ww_min_"+mlink ).click(function(){makePoint(mlink, link, nazwa, id);});
			
			
			min.onclick = function () {
                makePoint(mlink, link, nazwa, id);
                for (var i = 0; i < miniatury.length; i++)
                    miniatury[i].style.boxShadow = '';
                min.style.boxShadow = "0px 0px 20px #FF0000";
                //$( "#ww_min" ).hide();
				//min.style.border = '3px solid #E8272C';
                //min.style.borderRadius = '5px';
                wybMin = min;
            };
            miniatury.push(min);
		
			//console.log("event myszy:"+min.onclick );

            edW.appendChild(min);
            //edW.appendChild(document.createElement('&nbsp'));
        }
 
        
        /**
         * 
         * @param {type} mlink
         * @param {type} link
         * @param {type} nazwa
         * @param {type} id
         */
        function makePoint(mlink, link, nazwa, id) {
            panoDiv.style.cursor = 'crosshair';
            adding = true;
            pointToAdd = link;
            pointToAddName = nazwa;
            pointToAddID = id;
        }
        
        
        /**
         * Dodawanie punktów na podstawie linku z xml
         * 
         * @param {String} link
         */
        function panoPoints(link) {
            var x = panosXML.getElementsByTagName("pano");
            var nr;
            for (var i = 0; i < x.length; i++) {
                if (panosXML.getElementsByTagName("pano")[i].attributes.getNamedItem("link").value == link)
                    nr = i;
            }
            var y = panosXML.getElementsByTagName("pano")[nr].getElementsByTagName("punkt");
            for (var j = 0; j < y.length; j++) {
                var l = y[j].attributes.getNamedItem("link").value;
                if (y[j].attributes.getNamedItem("typ").value == '2')
                    l = 'info';
                //var lat=y[j].attributes.getNamedItem("lat").value;
                //var lon=y[j].attributes.getNamedItem("lon").value;	
                var n = y[j].attributes.getNamedItem("nazwa").value;
                var px = y[j].attributes.getNamedItem("px").value;
                var py = y[j].attributes.getNamedItem("py").value;
                var pz = y[j].attributes.getNamedItem("pz").value;
                var id = y[j].attributes.getNamedItem("id").value;
                addPoint(l, n, id, px, py, pz);
            }
        }
        
        
        /**
         * Wczytanie panoramy z linku
         * 
         * @param {type} link
         */
        function wczytajPano(link) {
            if (nameFromLink(link) != '') {
                tytul.innerHTML = nameFromLink(link).toUpperCase();
                tytul.setAttribute('align', 'center');
                //tytul.style.left=(panoDiv.clientWidth/2)-(tytul.clientWidth/2)+'px';
                //tytul.style.width = '100%';
                tytul.style.left=(panoDiv.getBoundingClientRect().width/2)-(tytul.getBoundingClientRect().width/2)+'px';
				tytul.style.display = '';
            } else {
                tytul.style.display = 'none';
            }

            tytul.innerHTML = 'Wczytywanie panoramy ' + nameFromLink(link).toUpperCase() + '<br><img src="' + imgkatalog + '/loading.gif" />';
            //tooltip.innerHTML='<center>Wczytywanie panoramy...<br><img src="'+imgkatalog+'/loading.gif" /></center>';
            //tooltip.style.display='';
            //tooltip.style.top = panoDiv.offsetWidth/2+'px';
            //tooltip.style.left= panoDiv.offsetHeight/2+'px';	
            punkty = [];
            if (scene.getObjectByName('sfera')) {
				scene.remove(scene.getObjectByName('sfera'));
            }
			sphere.dispose();
            while (scene.children.length > 0) {
				scene.remove(scene.children[0]);
			}
            var x = panosXML.getElementsByTagName("pano");
            for (var i = 0; i < x.length; i++) {
                if (x[i].attributes.getNamedItem("link").value == link)
                    panoID = x[i].attributes.getNamedItem("id").value;
            }
			sphereMaterial.dispose();
			sphereTexture.dispose();
			panoPoints(link);
            ID = IDfromLink(link);
            //THREE.ImageUtils.crossOrigin = '';
            var latlon = LatLonfromLink(link);
            Slat = parseInt(latlon[0]);
            Slon = parseInt(latlon[1]);
            latitude = parseInt(latlon[0]);
            longitude = parseInt(latlon[1]);
            

            ////plane do pomiarow
            var geometry = new THREE.PlaneGeometry(2500, 2500);
            var material = new THREE.MeshBasicMaterial({
                color: 0xffff00,
                opacity: 0,
                transparent: true
            });
            material.depthTest = false;
            material.depthWrite = false;

            pomiar_plane = new THREE.Mesh(geometry, material);

            pomiar_plane.rotation.x = -Math.PI / 2;
            pomiar_plane.position.set(0, -200, 0);

            scene.add(pomiar_plane);


            loader = new THREE.TextureLoader();
            loader.crossOrigin = '';


            loader.load(link,
                    function (texture) {
                        
						texture.minFilter = THREE.LinearFilter;
                        texture.magFilter = THREE.LinearFilter;
                        if(!settings.lowSpec){
							var maxAnisotropy = renderer.getMaxAnisotropy();
							texture.anisotropy = maxAnisotropy;
							}
                        // do something with the texture
						sphereTexture = texture;
                        sphereMaterial = new THREE.MeshBasicMaterial({
                            map: texture
                        });
                        // geometry + material = mesh (actual object)
                        var sphereMesh = new THREE.Mesh(sphere, sphereMaterial);
                        scene.add(sphereMesh);
                        sphereMesh.name = 'sfera';
                        //console.log('wczytano texture');
                        if (nameFromLink(link) != '') {
                            tytul.innerHTML = nameFromLink(link).toUpperCase();
                            tytul.setAttribute('align', 'center');
                            tytul.style.left=(panoDiv.getBoundingClientRect().width/2)-(tytul.getBoundingClientRect().width/2)+'px';
							//tytul.style.width = '100%';
                            tytul.style.display = '';
                        } else {
                            tytul.style.display = 'none';
                        }
                    }
            );

            ////podstawka z logiem

            var loaderPodst = new THREE.TextureLoader();
            loaderPodst.crossOrigin = '';
            if (logoLink != '')
                loaderPodst.load(logoLink,
                        function (texture) {

                            var canvas = document.createElement('canvas');
                            canvas.width = 420;
                            canvas.height = 420;
                            var context = canvas.getContext('2d');
                            context.fillStyle = 'rgba(0,0,0,0.5)';
                            //context.strokeStyle = 'rgba(0,0,0,1)';
                            context.lineWidth = 1;
                            context.beginPath();
                            context.arc(210, 210, 190, 0, 2 * Math.PI);
                            context.shadowColor = '#000';
                            context.shadowBlur = 20;
                            context.shadowOffsetX = 0;
                            context.shadowOffsetY = 0;
                            context.fill();
                            context.closePath();
                            //context.stroke();
                            //context.textAlign = "center"; 
                            //context.font = '26pt Calibri';
                            //context.fillStyle = 'white';
                            //if(context.measureText(nazwaBiura).width>280)context.font = '15pt Calibri';
                            //context.fillText(nazwaBiura,160,200);
                            //context.drawImage(texture.image,10,10);


                            if (texture.image.width > texture.image.height) {
                                //sprite.scale.set(200, texture.image.height/(texture.image.width/200), 2);
                                var skala = texture.image.width / 250;
                                context.drawImage(texture.image, 210 - (texture.image.width / skala / 2), 210 - (texture.image.height / skala / 2), texture.image.width / skala, texture.image.height / skala);
                            } else {
                                //sprite.scale.set(texture.image.width/(texture.image.heiht/200),200, 2);	
                                var skala = texture.image.height / 250;
                                context.drawImage(texture.image, 210 - (texture.image.width / skala / 2), 210 - (texture.image.height / skala / 2), texture.image.width / skala, texture.image.height / skala);
                            }
                            //sprite.scale.set(200, 200, 2);
                            var texCanv = new THREE.Texture(canvas);
                            texCanv.needsUpdate = true;

                            var Mat = new THREE.SpriteMaterial({
                                map: texCanv,
                                transparent: true,
                                color: 0xffffff
                            });
                            Mat.depthTest = false;
                            Mat.depthWrite = false;
                            var sprite = new THREE.Sprite(Mat);
                            scene.add(sprite);
                            sprite.position.set(0, -200, 0);
                            sprite.scale.set(200, 200, 200);
                            //var Mat = new THREE.Material({map: texCanv, transparent: true, color: 0xffffff})
                            //Mat.depthTest = false;
                            // Mat.depthWrite = false;
                            //var podstawka= new THREE.PlaneGeometry( 50, 50);
                            //var plane = new THREE.Mesh( podstawka, Mat );
                            //plane.position.set(0, -300, 0);
                            //scene.add( plane );

                        });
        }
        
        
        /**
         * Wczytywanie pliku xml (link, funkca do wykonania po wczytaniu)
         * 
         * @param {type} filename
         * @param {type} funkcja
         * @returns {undefined}
         */
        function loadXMLDoc(filename, funkcja) {
            var xhttp;
            if (window.XMLHttpRequest) {
                xhttp = new XMLHttpRequest();
            } else // code for IE5 and IE6
            {
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xhttp.overrideMimeType('text/xml');
            xhttp.open("GET", filename + "?anticache=" + new Date().getTime());

            xhttp.onreadystatechange = function () {
                if (xhttp.readyState == 4) {
                    //alert(xhttp.responseXML);
                    funkcja(xhttp.responseXML);
                }
            };
            xhttp.send();
        }
        
        
        /**
         * Dodawanie punktow
         * 
         * @param {type} link
         * @param {type} name
         * @param {type} id
         * @param {type} px
         * @param {type} py
         * @param {type} pz
         */
        function addPoint(link, name, id, px, py, pz) {
            //var map = THREE.ImageUtils.loadTexture( "skin/hs.png" );
            // var material = new THREE.SpriteMaterial( { map: map, color: 0xffffff, fog: true } );
            //  var sprite = new THREE.Sprite( material );
            var tex;
            if (link != 'info') {
                var canvas = document.createElement('canvas');
                canvas.width = 106;
                canvas.height = 128;
                var context = canvas.getContext('2d');
                var loader = new THREE.TextureLoader();
				loader.crossOrigin = '';
                if (NO_view) {
                    loader.load(imgkatalog + '/NO_hs.png', function (texture) {
                        context.save();
                        context.drawImage(texture.image, 0, 0);
                        tex = new THREE.Texture(canvas);
                        tex.needsUpdate = true;
                        hsMake(tex);
                    });
                } else
                    loader.load(imgkatalog + '/hs.png', function (texture) {
                        context.save();
                        context.drawImage(texture.image, 0, 0);
                        var loader2 = new THREE.TextureLoader();
                        var imgLink = minfromLink(link);
						loader2.crossOrigin = '';	
                        loader2.load(imgLink, function (texture) {
                            context.beginPath();
                            context.lineWidth = 3;
                            context.arc(52, 53, 44, 0, Math.PI * 2, true);
                            context.clip();
                            //context.globalCompositeOperation = 'screen';
                            //context.globalAlpha = 0.5;
                            context.drawImage(texture.image, -20, 0, 200, 120);
                            tex = new THREE.Texture(canvas);
                            tex.needsUpdate = true;
                            hsMake(tex);
                        });
                    });
            } else {
                var canvas = document.createElement('canvas');
                if (NO_view) {
                    canvas.width = 65;
                    canvas.height = 63;
                } else {
                    canvas.width = 33;
                    canvas.height = 33;
                }

                var context = canvas.getContext('2d');
                var loader = new THREE.TextureLoader();
                var graf;
                if (NO_view) {
                    graf = imgkatalog + '/NO_hsi.png';
                } else {
                    graf = imgkatalog + '/hsi.png';
                }
                loader.load(graf, function (texture) {
                    context.save();
                    context.drawImage(texture.image, 0, 0);
                    tex = new THREE.Texture(canvas);
                    tex.needsUpdate = true;
                    hsMake(tex);
                });
                //tex=imgkatalog+'/hsi.png';
            }


            //var loaderHs = new THREE.TextureLoader();
            // loaderHs.load(tex,
            function hsMake(texture) {
                // do something with the texture
                var hsMat = new THREE.SpriteMaterial({
                    map: texture,
                    transparent: true,
                    color: 0xffffff
                });
                var hsMaterial = new THREE.MeshBasicMaterial({
                    color: 0xee0000,
                    opacity: 0,
                    transparent: true
                });
                // geometry + material = mesh (actual object)
                hsMat.depthTest = false;
                hsMat.depthWrite = false;
                var sprite = new THREE.Sprite(hsMat); //tworzenie sprite`a punktu nawigacyjnego
                scene.add(sprite);
                sprite.position.set(px, py + 5, pz);
                sprite.scale.set(20, 25, 1.0);
                if (link == 'info')
                    sprite.scale.set(15, 15, 1.0);
                sprite.name = 'sp_' + link + '_' + px + '_' + py + '_' + pz; //nazwa sp_link do usuwania
                //console.log('sprite: ' + sprite.name);
                var hsGeo = new THREE.SphereGeometry(10, 8, 6);
                var hs = new THREE.Mesh(hsGeo, hsMaterial);
                //hs.rotation.x=lat-90;
                //hs.rotation.y=THREE.Math.degToRad(lon-90);

                //var x = 80 * Math.sin(THREE.Math.degToRad(90 - lat)) * Math.cos(THREE.Math.degToRad(lon));
                //var y = 80 * Math.cos(THREE.Math.degToRad(90 - lat));
                //var z = 80 * Math.sin(THREE.Math.degToRad(90 - lat)) * Math.sin(THREE.Math.degToRad(lon));

                hs.position.set(px, py + 5, pz);
                //hs.scale.set( 16, 16, 0.5 );
                hs.name = link;
                //var ud=[];
                // ud.push(name);
                // ud.push(id);

                //hs.userData.push(name);
                hs.userData = {
                    UName: name,
                    UId: id,
                    SName: sprite.name
                };
                //alert("name "+hs.name);
                //alert(''+80*Math.sin(lon)*Math.cos(lat)+' '+80*Math.sin(lon)*Math.sin(lat)+' '+80*Math.cos(lon)+' ');
                scene.add(hs);
                punkty.push(hs); //dodanie punktu do macierzy
                //console.log('Dodano punkt ' + px + ' ' + py + ' ' + pz);
            }
            /*,
             // Function called when download progresses
             function(xhr) {
             //((xhr.loaded / xhr.total * 100) + '% loaded');
             },
             // Function called when download errors
             function(xhr) {
             //console.log('An error happened');
             }
             ); */


            //scene.add( sprite );

        }
        
        
        /**
         * Dodawanie prefiksów do CSS
         */
        function getCssValuePrefix() {
            var rtrnVal = ''; //default to standard syntax
            var prefixes = ['-o-', '-ms-', '-moz-', '-webkit-'];

            // Create a temporary DOM object for testing
            var dom = document.createElement('div');

            for (var i = 0; i < prefixes.length; i++) {
                // Attempt to set the style
                dom.style.background = prefixes[i] + 'linear-gradient(#000000, #ffffff)';

                // Detect if the style was successfully set
                if (dom.style.background) {
                    rtrnVal = prefixes[i];
                }
            }

            dom = null;

            return rtrnVal;
        }
        
        
        /**
         * 
         * @param {type} pano_div
         */
        function buildInterfaceNO(pano_div) {
            var interv;
            var remote = document.createElement('img');
            remote.src = imgkatalog + "/NO_remote_bg.png";
            remote.style.bottom = 20 + 'px';
            remote.style.right = 20 + 'px';
            remote.style.position = 'absolute';
            remote.setAttribute("usemap", '#menu');
            pano_div.appendChild(remote);

            var mapa = document.createElement('map');
            mapa.name = 'menu';
            pano_div.appendChild(mapa);

            var up = document.createElement('area');
            up.setAttribute("shape", 'poly');
            up.setAttribute("coords", '31,6,42,29,53,27,64,30,73,6,53,0');
            up.setAttribute("href", '#');
            up.style.cursor = 'pointer';
            up.onmousedown = function () {
                interv = setInterval(function () {
                    latitude += 1;
                }, 10)
            };
            up.onmouseup = function () {
                clearInterval(interv)
            };
            up.onmouseout = function () {
                clearInterval(interv)
            };
            up.id = 'remote';
            showTooltip2(up, "W góre");
            mapa.appendChild(up);

            pano_div.style.position = 'relative';

            var down = document.createElement('area');
            down.setAttribute("shape", 'poly');
            down.setAttribute("coords", '42,78,54,79,64,77,72,101,53,105,34,101');
            down.setAttribute("href", '#');
            down.style.cursor = 'pointer';
            down.onmousedown = function () {
                interv = setInterval(function () {
                    latitude -= 1;
                }, 10)
            };
            down.onmouseup = function () {
                clearInterval(interv)
            };
            down.onmouseout = function () {
                clearInterval(interv)
            };
            down.id = 'remote';
            showTooltip2(down, "W dół");
            mapa.appendChild(down);

            var left = document.createElement('area');
            left.setAttribute("shape", 'poly');
            left.setAttribute("coords", '29,42,26,53,29,62,3,72,0,58,5,35');
            left.setAttribute("href", '#');
            left.style.cursor = 'pointer';
            left.onmousedown = function () {
                interv = setInterval(function () {
                    longitude -= 2;
                }, 10)
            };
            left.onmouseup = function () {
                clearInterval(interv)
            };
            left.onmouseout = function () {
                clearInterval(interv)
            };
            left.id = 'remote';
            showTooltip2(left, "W lewo");
            mapa.appendChild(left);

            var right = document.createElement('area');
            right.setAttribute("shape", 'poly');
            right.setAttribute("coords", '77,42,80,52,77,64,99,74,106,53,101,33');
            right.setAttribute("href", '#');
            right.style.cursor = 'pointer';
            right.onmousedown = function () {
                interv = setInterval(function () {
                    longitude += 2;
                }, 10)
            };
            right.onmouseup = function () {
                clearInterval(interv)
            };
            right.onmouseout = function () {
                clearInterval(interv)
            };
            right.id = 'remote';
            showTooltip2(right, "W prawo");
            mapa.appendChild(right);


            var play = document.createElement('img');
            play.src = imgkatalog + "/NO_remote_play.png";
            play.style.bottom = 64 + 'px';
            play.style.right = 64 + 'px';
            play.style.position = 'absolute';
            play.style.cursor = 'pointer';
            play.onclick = function () {
                if (autoRotate == false) {
                    autoRotate = true;
                    play.src = imgkatalog + "/NO_remote_pause.png";
                } else {
                    autoRotate = false;
                    play.src = imgkatalog + "/NO_remote_play.png";
                }
            };
            pano_div.appendChild(play);
            play.id = 'remote';
            showTooltip2(play, "Autoobracanie");
			
			
			var remote2 = document.createElement('img');
            remote2.src = imgkatalog + "/NO_zoom.png";
            remote2.style.bottom='50%';
			remote2.style.right = 20 + 'px';
            remote2.style.position = 'absolute';
            remote2.setAttribute("usemap", '#menu2');
			pano_div.appendChild(remote2);
			
			var mapa2 = document.createElement('map');
            mapa2.name = 'menu2';
            pano_div.appendChild(mapa2);
			//1,39,38,38,38,12,26,0,11,0,0,11
            var zoomUp = document.createElement('area');
            zoomUp.setAttribute("shape", 'poly');
            zoomUp.setAttribute("coords", '1,39,38,38,38,12,26,0,11,0,0,11');
            //zoomUp.setAttribute("href", '#');
            zoomUp.style.cursor = 'pointer';
            zoomUp.onmousedown = function () {
                interv = setInterval(function () {
                    camera.fov-=1;
					camera.updateProjectionMatrix();
					if (camera.fov < 30)
					camera.fov = 30;
				}, 10)
            };
            zoomUp.onmouseup = function () {
                clearInterval(interv)
            };
            zoomUp.onmouseout = function () {
                clearInterval(interv)
            };
            zoomUp.id = 'remote';
            showTooltip2(zoomUp, "Zbliżenie");
            mapa2.appendChild(zoomUp);
			//0,41,38,40,38,67,27,80,10,80,0,68
			var zoomDown = document.createElement('area');
            zoomDown.setAttribute("shape", 'poly');
            zoomDown.setAttribute("coords", '0,41,38,40,38,67,27,80,10,80,0,68');
            //zoomDown.setAttribute("href", '#');
            zoomDown.style.cursor = 'pointer';
            zoomDown.onmousedown = function () {
                interv = setInterval(function () {
                    camera.fov+=1;
					camera.updateProjectionMatrix();
					if (camera.fov > 140)
					camera.fov = 140;
                }, 10)
            };
            zoomDown.onmouseup = function () {
                clearInterval(interv)
            };
            zoomDown.onmouseout = function () {
                clearInterval(interv)
            };
            zoomDown.id = 'remote';
            showTooltip2(zoomDown, "Oddalenie");
            mapa2.appendChild(zoomDown);
			
			/*
             var plus=document.createElement('area');
             plus.setAttribute("shape",'poly');
             plus.setAttribute("coords", '1,53,19,53,19,28,8,28,1,34');
             plus.setAttribute("href",'#');
             plus.style.cursor='pointer';
             plus.onmousedown=function() {interv= setInterval(function(){ camera.fov -= 1; camera.updateProjectionMatrix();if (camera.fov < 30)camera.fov = 30; }, 10);};
             plus.onmouseup=function() {clearInterval(interv)};
             plus.onmouseout=function() {clearInterval(interv)};	
             plus.id='remote';
             showTooltip2(plus,"Zbliżenie");	
             mapa.appendChild(plus);
             
             var minus=document.createElement('area');
             minus.setAttribute("shape",'poly');
             minus.setAttribute("coords", '1,55,19,55,19,80,10,80,2,74');
             minus.setAttribute("href",'#');
             minus.style.cursor='pointer';
             minus.onmousedown=function() {interv= setInterval(function(){ camera.fov += 1; camera.updateProjectionMatrix();if (camera.fov > 140)camera.fov = 140; }, 10);};
             minus.onmouseup=function() {clearInterval(interv)};
             minus.onmouseout=function() {clearInterval(interv)};
             minus.id='remote';
             showTooltip2(minus,"Oddalenie");	
             mapa.appendChild(minus);
             */

            pano_div.style.position = 'relative';
        }
        
        
        /**
         * Budowanie przycisków
         * 
         * @param {type} pano_div
         */
        function buildInterface(pano_div) {
            
            function secondsToTime(secs) {
                var hours = Math.floor(secs / (60 * 60));

                var divisor_for_minutes = secs % (60 * 60);
                var minutes = Math.floor(divisor_for_minutes / 60);

                var divisor_for_seconds = divisor_for_minutes % 60;
                var seconds = Math.ceil(divisor_for_seconds);

                var obj = '';
                if (hours > 0)
                    obj += hours + ':';
                obj += minutes + ':';
                if (seconds < 10)
                    obj += '0';
                obj += seconds;
                return obj;
            }
            
            function makeFoto() {
                //var ctx=renderer.domElement.getContext('2d');
                //var imgData=ctx.getImageData(0,0,canvas.renderer.domElement,canvas.renderer.domElement);
                renderer.setSize(1920, 1080);
                camera.aspect = (1920*1.05) / 1080;
                camera.updateProjectionMatrix();
				render();
                screenshot = renderer.domElement.toDataURL();
                zdjImg.src = renderer.domElement.toDataURL();
                //renderer.setSize(sizeXold, sizeYold);
                camera.aspect = pano_div.offsetWidth / pano_div.offsetHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(pano_div.offsetWidth, pano_div.offsetHeight);
                render();
            }
            
            function parse(val) {
                var result = "Not found",
                        tmp = [];
                location.search
                        //.replace ( "?", "" ) 
                        // this is better, there might be a question mark inside
                        .substr(1)
                        .split("&")
                        .forEach(function (item) {
                            tmp = item.split("=");
                            if (tmp[0] === val)
                                result = decodeURIComponent(tmp[1]);
                        });
                return result;
            }

            function zdjOfZap() {
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else { // code for IE5 and IE6
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.overrideMimeType('image/octet-stream');
                xhttp.open("POST", "Moduly/Virgo/virgowwscreenshot.ashx?oferId=" + parse('ww') + "&id=upload&nameStr=images.jpg&orderNo=1&kindId=0&isToExport=" + +zdjEx.checked + "&isToWWW=" + +zdjWWW.checked + "&isToEmail=" + +zdjMail.checked + "&descriptionStr=-", true);
                var zwrot = screenshot.replace('data:image/png;base64,', '');
                xhttp.send(zwrot);
                alert("Zdjęcie zostało zapisane");
            }

			var helpW = document.createElement('div');
            var helpWin = document.createElement('center');
            helpW.style.color = 'white';
            helpWin.innerHTML = '<hr id="ww_hr"><div id="ww_bottomDiv" >Wciśnij lewy przycisk myszy i przeciągni mysz w wybranym kierunku aby obracać sceną<br><img style="opacity:0.5" src="' + imgkatalog + '/anim.gif" /></div>';
            helpW.appendChild(helpWin);
            helpW.style.display = "none";
            helpW.id='ww_help';
			helpW.style.width = '100%';
            helpW.style.height = 150 + 'px';
            helpW.style.bottom = 0 + 'px';
			helpW.style.position = 'absolute';
            pano_div.appendChild(helpW);
			
			var play_bt = document.createElement('a');
            var play = document.createElement('img');
            play.src = imgkatalog + "/rotate.png";
            play_bt.id='ww_autoobracanie';
			play_bt.style.left = 10 + 'px';
            play_bt.style.bottom = 5 + 'px';
            if(mov_mode){
				play_bt.style.left = 50 + 'px';
				play_bt.style.bottom = 25 + 'px';	
				}
			
			play_bt.style.position = 'absolute';
            play_bt.style.cursor = 'pointer';
            play_bt.onclick = function () {
                if (autoRotate == false) {
                    autoRotate = true;
                    play.src = imgkatalog + "/rotate_stop.png";
                } else {
                    autoRotate = false;
                    play.src = imgkatalog + "/rotate.png";
                }
            };
			play_bt.appendChild(play);
            pano_div.appendChild(play_bt);
            play.id = 'remote';
            showToolip(play_bt, "Autoobracanie");
			
			var help_bt= document.createElement('a');
            var help = document.createElement('img');
            help.src = imgkatalog + "/help.png";
            help_bt.style.left = 45 + 'px';
            help_bt.style.bottom = 5 + 'px';
			if(mov_mode){
				help_bt.style.left = 85 + 'px';
				help_bt.style.bottom = 25 + 'px';	
				}
            help_bt.style.position = 'absolute';
            help_bt.style.cursor = 'pointer';
            help_bt.onclick = function () {
				$( "#ww_min" ).hide();
                $( "#ww_help" ).slideToggle( "slow" );
			
            };
			help_bt.appendChild(help);
            pano_div.appendChild(help_bt);
            showToolip(help_bt, "Pomoc");
            pano_div.style.position = 'relative';

            /*
             var fullScreen = document.createElement('img');
             fullScreen.src = imgkatalog+"/fs.png";
             var fullScreenA = document.createElement('a');
             fullScreenA.id = 'fullScreen';
             fullScreen.onclick = function fullScreenMode() {
             
             if (document.webkitFullscreenElement)
             {
             if (pano_div.cancelFullscreen) {
             pano_div.cancelFullscreen();
             } else if (pano_div.msCancelFullscreen) {
             pano_div.msCancelFullscreen();
             } else if (pano_div.mozCancelFullScreen) {
             pano_div.mozCancelFullScreen();
             } else if (pano_div.webkitCancelFullscreen) {
             pano_div.webkitCancelFullscreen();}
             //document.webkitCancelFullScreen();
             
             }
             else
             {
             pano_div.style.width = screen.width;
             pano_div.style.height = screen.height;
             renderer.setSize(screen.width, screen.height);
             edW.style.width = screen.width;
             if (pano_div.requestFullscreen) {
             pano_div.requestFullscreen();
             } else if (pano_div.msRequestFullscreen) {
             pano_div.msRequestFullscreen();
             } else if (pano_div.mozRequestFullScreen) {
             pano_div.mozRequestFullScreen();
             } else if (pano_div.webkitRequestFullscreen) {
             pano_div.webkitRequestFullscreen();
             }
             
             
             }
             
             
             document.addEventListener('webkitfullscreenchange', exitHandler, false);
             document.addEventListener('mozfullscreenchange', exitHandler, false);
             document.addEventListener('fullscreenchange', exitHandler, false);
             document.addEventListener('MSFullscreenChange', exitHandler, false);
             
             function exitHandler(){
             fsFit(pano_div);	
             }
             
             fsFit(pano_div);
             };
             
             fullScreenA.href = "#";
             fullScreenA.style.position = 'absolute';
             fullScreenA.style.top = 110+'px';
             fullScreenA.style.left = 10+'px';
             fullScreenA.appendChild(fullScreen);
             fullScreenA.style.opacity='0.3';
             showToolip(fullScreenA,"Pelny Ekran");
             //pano_div.appendChild(fullScreenA);   //do poprawy dla Firefox*/
            if (mov_mode == true) {
                var play_mov = document.createElement('img');
                play_mov.src = imgkatalog + "/pause_mov.png";
                play_mov.style.bottom = 35 + 'px';
                play_mov.style.left = 30 + 'px';
                play_mov.style.position = 'absolute';
                play_mov.style.cursor = 'pointer';
                play_mov.onclick = function () {
                    if (video.paused == false) {
                        video.pause();
                        play_mov.src = imgkatalog + "/play_mov.png";
                    } else {
                        video.play();
                        play_mov.src = imgkatalog + "/pause_mov.png";
                    }
                };
                pano_div.appendChild(play_mov);
                play_mov.id = 'remote';
                showTooltip2(play_mov, "Zatrzymaj/Wznów film");

                var interval;


                var bar = document.createElement('div');
                bar.style.position = 'absolute';
                bar.style.bottom = 27 + 'px';
                bar.style.left = 5 + '%';
                bar.style.height = 4 + 'px';
                bar.style.width = '90%';
                bar.style.backgroundColor = 'rgba(20, 20, 20, 0.7)';
                pano_div.appendChild(bar);

                var barBuf = document.createElement('div');
                barBuf.style.position = 'absolute';
                barBuf.style.bottom = 27 + 'px';
                barBuf.style.left = 5 + '%';
                barBuf.style.height = 4 + 'px';
                //barCur.style.width='90%';
                barBuf.style.backgroundColor = 'rgba(200, 200, 200, 0.4)';
                pano_div.appendChild(barBuf);

                var barCur = document.createElement('div');
                barCur.style.position = 'absolute';
                barCur.style.bottom = 27 + 'px';
                barCur.style.left = 5 + '%';
                barCur.style.height = 4 + 'px';
                //barCur.style.width='90%';
                barCur.style.backgroundColor = 'rgba(255, 20, 20, 1)';
                pano_div.appendChild(barCur);



                var curtime = document.createElement('div');
                curtime.style.position = 'absolute';
                curtime.style.bottom = 7 + 'px';
                curtime.style.left = 30 + 'px';
                interval = setInterval(function () {
                    curtime.innerHTML = secondsToTime(video.currentTime) + '/' + secondsToTime(video.duration);
                    barBuf.style.width = (video.buffered.end(0) / video.duration) * 90 + '%';
                    barCur.style.width = (video.currentTime / video.duration) * 90 + '%';
                }, 100);
                curtime.style.position = 'absolute';
                curtime.style.fontFamily = 'Khand,sans-serif';
                curtime.style.color = 'white';
                pano_div.appendChild(curtime);
            }
            if (edycja == true) {
                
                edWi = document.createElement('div');
                edWi.id = 'ww_inf';
                edWi.style.position = 'absolute';
                edWi.style.bottom = 0 + 'px';
                edWi.style.left = 0 + 'px';
                edWi.style.height = 130 + 'px';
                edWi.style.width = '100%';
                edWi.style.color = 'white';
                edWiIn = document.createElement('center');
                edWiIn.innerHTML = '<hr id="ww_hr">Treść dodawanego punktu';
                edWitxt = document.createElement('textarea');
                edWitxt.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
                edWitxt.style.height = 35 + 'px';
                edWitxt.style.width = '90%';
                edWiIn.appendChild(edWitxt);
                edWi.appendChild(edWiIn);
                edWi.style.display = 'none';
				edWi.style.zindex= 1000;
                pano_div.appendChild(edWi);
				
				var addBT = document.createElement('img');
                addBT.src = imgkatalog + "/add.png";
                var addBTA = document.createElement('a');
                addBTA.id = 'zoomUp';
                addBTA.onclick = function () {
					 $( "#ww_help" ).hide();
					 $( "#ww_inf" ).hide();
					 $( "#ww_min" ).slideToggle( "slow" );
                    };
                addBTA.href = "#";
                addBTA.style.position = 'absolute';
                //addBT.style.width=55+'px';
                addBTA.style.bottom = 5 + 'px';
                addBTA.style.right = 205 + 'px';
                addBTA.appendChild(addBT);
                //addBTA.style.opacity='0.3';
                showToolip(addBTA, "Dodaj przejscie do kolejnej sceny");
                pano_div.appendChild(addBTA);

                var addiBT = document.createElement('img');
                addiBT.src = imgkatalog + "/addi.png";
                var addiBTA = document.createElement('a');
                addiBTA.id = 'zoomUp';
                addiBTA.onclick = function () {
					$( "#ww_help" ).hide();
					$( "#ww_min" ).hide();
					if (edWi.style.display == 'none') {
						adding = true;
                        pointToAdd = 'info';
						}
					$( "#ww_inf" ).slideToggle( "slow" );
				   
                };
                addiBTA.href = "#";
                addiBTA.style.position = 'absolute';
                //addiBT.style.width=55+'px';
                addiBTA.style.bottom = 5 + 'px';
                addiBTA.style.right = 170 + 'px';
                addiBTA.appendChild(addiBT);
                //addiBTA.style.opacity='0.3';
                showToolip(addiBTA, "Dodaj punkt z informacją");
                pano_div.appendChild(addiBTA);


                delBT = document.createElement('img');
                delBT.src = imgkatalog + "/del.png";
                var delBTA = document.createElement('a');
                delBTA.id = 'zoomUp';
                delBTA.onclick = function () {
                    if (delMode == false) {
                        delMode = true;
                        delBT.src = imgkatalog + "/del_a.png";
                        //delBT.style.border = '2px solid #E8272C';
                        //delBT.style.borderRadius = '5px';
                    } else {
                        delMode = false;
                        delBT.src = imgkatalog + "/del.png";
                    }
                };
                delBTA.href = "#";
                delBTA.style.position = 'absolute';
                //delBT.style.width=55+'px';
                delBTA.style.bottom = 5 + 'px';
                delBTA.style.right = 135 + 'px';
                //delBTA.style.opacity='0.3';
                showToolip(delBTA, "Usuń punkt ze sceny");
                delBTA.appendChild(delBT);
                pano_div.appendChild(delBTA);

                var saveBT = document.createElement('img');
                saveBT.src = imgkatalog + "/save.png";
                var saveBTA = document.createElement('a');
                saveBTA.id = 'zoomUp';
                saveBTA.onclick = function () {
                    savePano(true);
                };
                saveBTA.href = "#";
                saveBTA.style.position = 'absolute';
                //saveBT.style.width=55+'px';
                saveBTA.style.bottom = 5 + 'px';
                saveBTA.style.right = 95 + 'px';
                saveBTA.appendChild(saveBT);
                //saveBTA.style.opacity='0.3';
                showToolip(saveBTA, "Zapisz zmiany");
                pano_div.appendChild(saveBTA);

                var zdjW = document.createElement('div');
                zdjW.id = 'zdjWindow';
                zdjW.style.position = 'absolute';
                zdjW.style.top = 30 + 'px';
                zdjW.style.left = 150 + 'px';
                zdjW.style.height = 400 + 'px';
                zdjW.style.width = 400 + 'px';
                zdjW.style.color = 'white';
                zdjW.style.background = 'rgba(0, 0, 0, 0.3)';
                zdjW.style.padding = 5 + 'px';
                zdjW.style.border = '1px solid rgba(0, 0, 0, 1)';
                zdjW.style.borderRadius = '5px';
                zdjW.style.display = 'none';
                pano_div.appendChild(zdjW);

                var zdjBT = document.createElement('img');
                zdjBT.src = imgkatalog + "/zdj.png";
                var zdjBTA = document.createElement('a');
                zdjBTA.id = 'makeZdj';
                zdjBTA.onclick = function () {
                    makeFoto();
                    zdjW.style.display = '';
                };
                zdjBTA.href = "#";
                zdjBTA.style.position = 'absolute';
                //zdjBT.style.width = 55+'px';
                zdjBTA.style.bottom = 5 + 'px';
                zdjBTA.style.right = 55 + 'px';
                zdjBTA.appendChild(zdjBT);
                //zdjBTA.style.opacity='0.3';
                showToolip(zdjBTA, "Zrób zdjęcie bierzącego ujęcia");
                pano_div.appendChild(zdjBTA);

                var stPozBT = document.createElement('img');
                stPozBT.src = imgkatalog + "/start.png";
                var stPozBTA = document.createElement('a');
                stPozBTA.id = 'stPoz';
                stPozBTA.onclick = function () {
                    Slat = latitude;
                    Slon = longitude;
                    alert('Nowa pozycja startowa ustawiona');
                };
                stPozBTA.href = "#";
                stPozBTA.style.position = 'absolute';
                // stPozBT.style.width = 55+'px';
                stPozBTA.style.bottom = 5 + 'px';
                stPozBTA.style.right = 15 + 'px';
                stPozBTA.appendChild(stPozBT);
                //stPozBTA.style.opacity='0.3';
                showToolip(stPozBTA, "Ustaw ujęcie<br>jako początkowe");
                pano_div.appendChild(stPozBTA);

                var zdjImg = document.createElement('img');
                var screenshot;
                zdjImg.style.position = 'relative';
                zdjImg.style.width = 390 + 'px';
                zdjImg.style.top = 5 + 'px';
                zdjImg.style.left = 5 + 'px';
                zdjW.appendChild(zdjImg);

                var zdjDysk = document.createElement('div');
                zdjDysk.style.background = 'rgba(0, 0, 0, 0.5)';
                //zdjDysk.style.boxShadow="0px 0px 5px black"
                zdjDysk.style.position = 'absolute';
                zdjDysk.style.cursor = 'pointer';
                zdjDysk.style.color = 'white';
                zdjDysk.style.bottom = 10 + 'px';
                zdjDysk.style.width = 100 + 'px';
                zdjDysk.style.padding = 5 + 'px';
                zdjDysk.style.border = '1px solid rgba(0, 0, 0, 1)';
                zdjDysk.style.borderRadius = '5px';

				zdjDysk.innerHTML = 'Zapisz na dysk';
                zdjDysk.download = 'pano.png';
                //zdjDysk.href = 'data:,' + screenshot.replace('image/png', 'image/octet-stream');
                //zdjDysk.onclick=function(){var link = document.createElement('a');link.download = "pano.png";link.href = screenshot.replace('image/png', 'image/octet-stream');link.innerHTML='aaa';link.click();}
                zdjDysk.onclick = function () {
                    var link = document.createElement('a');
                    link.download = "pano.png";
                    //link.href = zdjImg.src.replace('image/png', 'image/octet-stream');
                    /*zdjW.appendChild(link);
                    link.click();*/
					var image_data = atob(zdjImg.src.split(',')[1]);
					// Use typed arrays to convert the binary data to a Blob
					var arraybuffer = new ArrayBuffer(image_data.length);
					var view = new Uint8Array(arraybuffer);
					for (var i=0; i<image_data.length; i++) {
						view[i] = image_data.charCodeAt(i) & 0xff;
						}
					try {
						// This is the recommended method:
						var blob = new Blob([arraybuffer], {type: 'application/octet-stream'});
						} catch (e) {
						// The BlobBuilder API has been deprecated in favour of Blob, but older
						// browsers don't know about the Blob constructor
						// IE10 also supports BlobBuilder, but since the `Blob` constructor
						//  also works, there's no need to add `MSBlobBuilder`.
						var bb = new (window.WebKitBlobBuilder || window.MozBlobBuilder);
						bb.append(arraybuffer);
						var blob = bb.getBlob('application/octet-stream'); // <-- Here's the Blob
						}
					// Use the URL object to create a temporary URL
					var url = (window.webkitURL || window.URL).createObjectURL(blob);
					//location.href = url; // <-- Download!
					link.href = url;
					zdjW.appendChild(link);
                    link.click();
					
					
                };
                //zdjDysk.onclick=function(){var url = zdjImg.src.replace('image/png', 'image/octet-stream');window.location.href=url;}
                //zdjDysk.onclick=function(){window.location.href = screenshot.replace('image/png', 'image/octet-stream');}
                zdjW.appendChild(zdjDysk);



                var zdjOf = document.createElement('div');
                zdjOf.style.position = 'absolute';
                zdjOf.style.cursor = 'pointer';
                zdjOf.style.bottom = 10 + 'px';
                zdjOf.style.left = 130 + 'px';
                zdjOf.style.width = 110 + 'px';
                zdjOf.style.color = 'white';
                zdjOf.style.background = 'rgba(0, 0, 0, 0.7)';
                zdjOf.style.padding = 5 + 'px';
                zdjOf.style.border = '1px solid rgba(0, 0, 0, 1)';
                zdjOf.style.borderRadius = '5px';
                zdjOf.innerHTML = 'Zapisz do oferty';
                zdjOf.onclick = function () {
                    zdjOfZap();
                };
                zdjW.appendChild(zdjOf);


                var zdjL = document.createElement('label');
                zdjL.style.fontFamily = 'Khand,sans-serif';
                zdjL.innerHTML = "Publikacja:  Eksporty&nbsp&nbsp&nbsp&nbsp&nbspWWW&nbsp&nbsp&nbsp&nbsp&nbspEmail";
                zdjL.style.position = 'absolute';
                zdjL.style.bottom = 50 + 'px';
                zdjL.style.left = 10 + 'px';
                zdjW.appendChild(zdjL);

                var zdjEx = document.createElement('input');
                zdjEx.setAttribute("type", "checkbox");
                zdjEx.style.position = 'absolute';
                zdjEx.style.bottom = 50 + 'px';
                zdjEx.style.left = 153 + 'px';
                zdjEx.checked = true;
                zdjW.appendChild(zdjEx);

                var zdjWWW = document.createElement('input');
                zdjWWW.setAttribute("type", "checkbox");
                zdjWWW.style.position = 'absolute';
                zdjWWW.style.bottom = 50 + 'px';
                zdjWWW.style.left = 220 + 'px';
                zdjWWW.checked = true;
                zdjW.appendChild(zdjWWW);

                var zdjMail = document.createElement('input');
                zdjMail.setAttribute("type", "checkbox");
                zdjMail.style.position = 'absolute';
                zdjMail.style.bottom = 50 + 'px';
                zdjMail.style.left = 285 + 'px';
                zdjMail.checked = true;
                zdjW.appendChild(zdjMail);


                var zdjAn = document.createElement('div');
                zdjAn.style.position = 'absolute';
                zdjAn.style.cursor = 'pointer';
                zdjAn.style.bottom = 10 + 'px';
                zdjAn.style.left = 270 + 'px';
                zdjAn.style.width = 100 + 'px';
                zdjAn.style.background = 'rgba(0, 0, 0, 0.7)';
                zdjAn.style.padding = 5 + 'px';
                zdjAn.style.border = '1px solid rgba(255, 0, 0, 1)';
                zdjAn.style.borderRadius = '5px';
                zdjAn.innerHTML = 'Zamknij';
                zdjAn.onclick = function () {
                    zdjW.style.display = 'none';
                };
                zdjW.appendChild(zdjAn);


                //edW = document.createElement('div');
                edW.id = 'ww_min';
                edW.style.position = 'absolute';
                edW.style.bottom = 0 + 'px';
                edW.style.left = 0 + 'px';
                edW.style.textAlign = "center";
				edW.style.height = 130 + 'px';
				edWmin.id='ww_edWmin';
				edWmin.style.overflowY='scroll';
				edWmin.style.textAlign = "center";
				edWmin.style.height='100px';
				edW.style.overflowY='auto';
                edW.style.width = '100%';
                //edW.style.background = 'rgba(0, 0, 0, 1)';
                //edW.style.color = 'white';
                //edW.innerHTML = '<hr id="ww_hr">Wybierz scene docelową<hr id="ww_hr">';
               // edW.style.display = 'none';
                //edW.style.opacity = 0;


                
				
				
                mierzW = document.createElement('div');
                mierzW.id = 'ww_pomiar';
                mierzW.style.position = 'absolute';
                mierzW.style.bottom = 0 + 'px';
                mierzW.style.left = 0 + 'px';
                mierzW.style.height = 130 + 'px';
                mierzW.style.width = '100%';
                mierzW.style.color = 'white';
                var mierzWin = document.createElement('center');
                mierzWin.innerHTML = '<hr id="ww_hr">Aby dokonać pomiaru kliknij na podłodze pomieszczenia a następnie przesuń mysz w kieruku pomiaru<br>';
                mierzWin.innerHTML += '<table style="color:white"><tr><td style="vertical-align:middle;padding-right:5px;"><b>Typ pomiaru</b></td><td style="padding-right:10px;border-right: thick double rgba(0, 0, 0, 0.4);"><form style="margin:0px"><input type="radio" name="typPomiaru" checked="true" id="odleglosc">Odleglość<br><input type="radio" name="typPomiaru" id="powierzchnia">Powierzchnia</form></td><td style="padding-left:5px;">Wysokość obiektywu <input style="width:50px" value="1.2" id="wysokosc" type="text" onkeyup="checkInput(this)"/>m</td>';

                mierzW.appendChild(mierzWin);
                mierzW.style.display = 'none';
                mierzW.style.opacity = 0;
                pano_div.appendChild(mierzW);

                var mierzBT = document.createElement('img');
                mierzBT.src = imgkatalog + "/measure.png";
                var mierzBTA = document.createElement('a');
                mierzBTA.id = 'zoomUp';
                mierzBTA.onclick = function () {
                    panoDiv.style.cursor = 'crosshair';
                    if (pomiar) {
                        pomiar = false;
                    } else {
                        pomiar = true;
                    }
                    ;
                    var licz = latitude;
                    var licz2 = 0;
                    var wart = Math.abs(licz + 90) / 50;
                    var inter = setInterval(function () {
                        latitude = licz;
                        licz -= wart;
                        camera.fov += 1;
                        camera.updateProjectionMatrix();
                        licz2++;
                        if (pomiar) {
                            mierzW.style.display = '';
                            mierzW.style.bottom = licz2 + 'px';
                            mierzW.style.opacity = licz2 * 2;
                        } else {
                            mierzW.style.bottom = 45 - licz2 + 'px';
                            mierzW.style.opacity = 1 - licz2;

                        }
                    }, 10);
                    var stoper = setInterval(function () {
                        clearInterval(inter);
                        clearInterval(stoper);
                        //if(mierzW.style.opacity<0.2)mierzW.style.display='none'

                    }, 550);
                    //latitude=-90;

                };
                mierzBTA.href = "#";
                mierzBTA.style.position = 'absolute';
                mierzBTA.style.bottom = 5 + 'px';
                mierzBTA.style.right = 225 + 'px';
                mierzBTA.appendChild(mierzBT);
                showToolip(mierzBTA, "Wirtualny pomiar");
                //pano_div.appendChild(mierzBTA);


            }
            //var test2BT = document.createElement('img');
            //test2BT.src = "skin/minus.png";
            //var test2BTA=document.createElement('a');
            //test2BTA.id = 'zoomUp';
            //test2BTA.onclick=function(){scene.remove( scene.getObjectByName('sfera'));}
            //test2BTA.href="#";
            //test2BTA.style.position='absolute';
            //test2BTA.style.top=200;
            //test2BTA.style.left=10;
            //test2BTA.appendChild(test2BT)
            //pano_div.appendChild(test2BTA);
            //MadeBy
            var madeBy = document.createElement('a');
            madeBy.style.position = 'absolute';
            madeBy.href = 'http://galactica.pl';
            madeBy.innerHTML = 'Wirtualne Wizyty Galactica';
            madeBy.style.right = 0 + 'px';
            madeBy.style.bottom = 0 + 'px';
            //pano_div.appendChild(madeBy);

        }
        
        
        /**
         * Restrykcja znaków dla inputa
         * 
         * @param {type} ob
         */
        function checkInput(ob) {
            var invalidChars = /[^0-9.]/gi;
            if (invalidChars.test(ob.value)) {
                ob.value = ob.value.replace(invalidChars, "");
            }
        }
        
        
        /**
         * Pokazywnie tooltipa
         * 
         * @param {type} obj
         * @param {type} text
         */
        function showToolip(obj, text) {

            var tooltip2 = document.createElement('div');
            tooltip2.style.position = 'absolute';
            tooltip2.id='ww_tooltip';
			tooltip2.style.display = 'none';
            tooltip2.style.width = 100 + 'px';
  
			//console.log(obj.style.left+'< >'+obj.style.right+' '+obj.id );
			if(obj.style.right!=''){tooltip2.style.right=0+'px';}
			if(obj.style.left!=''){tooltip2.style.left=0+'px';}
			tooltip2.innerHTML = text;
            tooltip2.style.bottom = 35 + "px";
            obj.appendChild(tooltip2);
			//console.log('odstęp: '+$('#'+obj.id+'>#ww_tooltip').offset().left);	
            obj.addEventListener("mouseover", poka, false);
            obj.addEventListener("mouseout", chowaj, false);

            function poka(event) {           
                tooltip2.style.display = '';
            }

            function chowaj(event) {
                tooltip2.style.display = 'none';
            }
        }
        
        
        /**
         * 
         * @param {type} obj
         * @param {type} text
         */
        function showTooltip2(obj, text) {
            obj.addEventListener("mouseover", poka, false);
            obj.addEventListener("mouseout", chowaj, false);
            //var interv;
           //var intervStop;
			var rect = panoDiv.getBoundingClientRect();
            function poka(event) {
                if (obj.id !== 'remote') {
						tooltip.innerHTML = text + '<br><img src="' + obj.src + '" height="120px" />';
                        tooltip.style.display = '';
						var rect2 = tooltip.getBoundingClientRect();
						tooltip.style.top = rect.height-(270) + 'px';
					tooltip.style.left = (rect.width/2)-(rect2.width/2) + 'px';
					}
				
				if (text !== '') {
                    if (obj.id !== 'remote') {
						tooltip.innerHTML = text + '<br><img src="' + obj.src + '" height="120px" />';
                        tooltip.style.display = '';
						var rect2 = tooltip.getBoundingClientRect();
						tooltip.style.top = rect.height-(270) + 'px';
                        tooltip.style.left = (rect.width/2)-(rect2.width/2) + 'px';
						
						//tooltip.style.top = -rect.top+event.clientY - 190 + 'px';
                        //tooltip.style.left = -rect.left+event.clientX - 70 + 'px';
                    } else {
                        
						tooltip.innerHTML = text;
                        tooltip.style.display = '';
                        tooltip.style.top = -rect.top+event.clientY - 50 -panoDiv.getBoundingClientRect().top + 'px';
                        tooltip.style.left = -rect.left+event.clientX - 55 -panoDiv.getBoundingClientRect().left+ 'px';
						//console.log('tooltip '+event.clientY+' '+event.clientX);
					}
                }
                obj.style.opacity = '1';
            }

            function chowaj(event) {
                tooltip.innerHTML = '';
                tooltip.style.display = 'none';
                tooltip.style.top = 0 + 'px';
                tooltip.style.left = 0 + 'px';
                obj.style.opacity = '0.7';
            }
        }
        
        
        /**
         * Zapisanie panoramy
         * 
         * @param {type} komunikat
         */
        function savePano(komunikat) {
            var xhttp;
            if (window.XMLHttpRequest) {
                xhttp = new XMLHttpRequest();
            }
            else { // code for IE5 and IE6
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xhttp.overrideMimeType('text/xml');
            xhttp.open("POST", "/Moduly/Virgo/virOfertaWirtualnaWizyta.aspx", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            var zwrot = '{"id":"' + ID + '", "sLat":"' + Slat + '", "sLon":"' + Slon + '", punkty:[';
            for (var i = 0; i < punkty.length; i++) {
                var typ = 0;
                if (punkty[i].name == 'info')
                    typ = 2;
                zwrot += '{"id":"' + punkty[i].userData.UId + '", "nazwa":"' + punkty[i].userData.UName + '", "px":"' + punkty[i].position.x + '", "py":"' + punkty[i].position.y + '", "pz":"' + punkty[i].position.z + '", "typ":' + typ + '}';
                if (i < punkty.length - 1)
                    zwrot += ',';
            }
            zwrot += ']}';
            xhttp.send(zwrot);
            if (komunikat == true)
                alert("Zmiany zostały zapisane");
        }
        
        
        /**
         * 
         * @param {type} link
         */
        function nameFromLink(link) {
            var x = panosXML.getElementsByTagName("pano");
            var nr;
            for (var i = 0; i < x.length; i++) {
                if (x[i].attributes.getNamedItem("link").value == link)
                    nr = x[i].attributes.getNamedItem("nazwa").value;
            }
            return nr;
        }
        
        
        /**
         * 
         * @param {type} link
         */
        function IDfromLink(link) {
            var x = panosXML.getElementsByTagName("pano");
            var nr;
            for (var i = 0; i < x.length; i++) {
                if (x[i].attributes.getNamedItem("link").value == link)
                    nr = x[i].attributes.getNamedItem("id").value;
            }
            return nr;
        }


        /**
         * 
         * @param {type} link
         */
        function minfromLink(link) {
            var x = panosXML.getElementsByTagName("pano");
            var nr;
            for (var i = 0; i < x.length; i++) {
                if (x[i].attributes.getNamedItem("link").value == link)
                    nr = x[i].attributes.getNamedItem("min").value;
            }
            return nr;
        }


        /**
         * 
         * @param {type} link
         */
        function LatLonfromLink(link) {
            var x = panosXML.getElementsByTagName("pano");
            var nr = [];
            for (var i = 0; i < x.length; i++) {
                if (x[i].attributes.getNamedItem("link").value == link) {
                    if (x[i].attributes.getNamedItem("slat") != null) {
                        nr.push(x[i].attributes.getNamedItem("slat").value);
                    } else {
                        nr.push(0);
                    }
                    if (x[i].attributes.getNamedItem("slon") != null) {
                        nr.push(x[i].attributes.getNamedItem("slon").value);
                    } else {
                        nr.push(0);
                    }
                }
            }
            return nr;
        }
        
        
        /**
         * Rysowanie labeli z odległościami
         * 
         * @param {type} a
         * @param {type} b
         * @param {type} biezacy
         */
        function rysujDystans(a, b, biezacy) {
            biezacy = biezacy || false;
            var mnoznik = 0.01;
            var odl = Math.round(a.distanceTo(b)) * mnoznik;
            if (odl == 0) {
                //console.log('ax:' + a.x + ' ay:' + a.y + ' az:' + a.z + '|bx:' + b.x + ' by:' + b.y + ' bz:' + b.z + ' dist:' + a.distanceTo(b))
            }
            ;
            var label = document.createElement('canvas');
            label.width = 128;
            label.height = 32;
            var context = label.getContext('2d');
            context.textAlign = "center";
            context.font = '26pt Calibri';
            context.fillStyle = 'white';

            context.strokeText(odl + 'm', label.width / 2, label.height / 2 + 6);
            context.fillText(odl + 'm', label.width / 2, label.height / 2 + 6);
            //console.log(odl+'m '+label.width/2+' '+label.height/2);
            //panoDiv.appendChild(label);
            var texCanv = new THREE.Texture(label);
            texCanv.needsUpdate = true;

            var Mat = new THREE.SpriteMaterial({
                map: texCanv,
                transparent: true
            });
            Mat.depthTest = false;
            Mat.depthWrite = false;
            var sprite = new THREE.Sprite(Mat);
            if (biezacy == true) {
                sprite.name = 'dystans_bierzacy';
            } else {
                sprite.name = 'dystans';
            }
            //sprite.name='dystans';
            scene.add(sprite);
            sprite.position.set((a.x + b.x) / 2, (a.y + b.y) / 2, (a.z + b.z) / 2);
            sprite.scale.set(64, 16, 1);
            //console.log('dystans'+odl+'m'+sprite.position.x+' '+sprite.position.z+' '+sprite.position.y);
        }
        
        
        /**
         * Wciśniecie przycisku myszy (przesuwanie panoramy, klik na punkty)
         * 
         * @param {type} event
         */
        function onDocumentMouseDown(event) {
            panoDiv.style.cursor = 'all-scroll';

            event.preventDefault();
            manualControl = true;

            //savedX = event.clientX;
            //savedY = event.clientY;

            savedLongitude = longitude;
            savedLatitude = latitude;
			
			 var rect = panoDiv.getBoundingClientRect();
			//eventy dotyku
			if(event.touches!=undefined){
				if ( event.touches.length === 1 ) {
					tochMove=true;
					mouse.x = ((event.touches[ 0 ].pageX - rect.left) / renderer.domElement.clientWidth) * 2 - 1;//event.touches[ 0 ].pageX
					mouse.y = -((event.touches[ 0 ].pageY - rect.top) / renderer.domElement.clientHeight) * 2 + 1;
					savedX = event.touches[ 0 ].pageX;
					savedY = event.touches[ 0 ].pageY;
					
					}
				}else{
					//przeliczanie vektora klikniec myszy
					tochMove=false;
					mouse.x = ((event.clientX - rect.left) / renderer.domElement.clientWidth) * 2 - 1;
					mouse.y = -((event.clientY - rect.top) / renderer.domElement.clientHeight) * 2 + 1;
					savedX = event.clientX;
					savedY = event.clientY;
				}
				
			//mouse.x = (event.clientX / renderer.domElement.clientWidth) * 2 - 1;
            //mouse.y = -(event.clientY / renderer.domElement.clientHeight) * 2 + 1.07;
            raycaster.setFromCamera(mouse, camera);
            var intersects = raycaster.intersectObjects(punkty);
            if (intersects.length > 0) {

                if (delMode == false) {
                    if (intersects[0].object.name != 'info') {
                        if (edycja == true) {
                            savePano(false);
                            loadXMLDoc(xmlLink, processXMLEdit);
                        }
                        wczytajPano(intersects[0].object.name);
                    }
                } else {

                    for (i = 0; i < punkty.length; i++) {
                        if (punkty[i] == intersects[0].object)
                            punkty.splice(i, 1);
                    }

                    var py = intersects[0].object.position.y - 5;
                    //console.log('py: '+py+' '+intersects[ 0 ].object.position.y);
                    //console.log('usuwane: ' + 'sp_' + intersects[0].object.name + '_' + intersects[0].object.position.x + '_' + py + '_' + intersects[0].object.position.z);
                    //scene.remove(scene.getObjectByName('sp_' + intersects[ 0 ].object.name + '_' + intersects[ 0 ].object.position.x + '_' + py+10 + '_' + intersects[ 0 ].object.position.z));
                    //scene.remove(scene.getObjectByName('sp_' + intersects[ 0 ].object.name + '_' + intersects[ 0 ].object.position.x + '_' + py/10 + '_' + intersects[ 0 ].object.position.z));
                    scene.remove(scene.getObjectByName('sp_' + intersects[0].object.name + '_' + intersects[0].object.position.x + '_' + py + '_' + intersects[0].object.position.z));
                    scene.remove(scene.getObjectByName(intersects[0].object.userData.SName));
                    py = intersects[0].object.position.y + 5;
                    scene.remove(scene.getObjectByName('sp_' + intersects[0].object.name + '_' + intersects[0].object.position.x + '_' + py + '_' + intersects[0].object.position.z));
                    scene.remove(intersects[0].object);
                    delMode = false;
                    delBT.src = imgkatalog + "/del.png";

                }

                //alert("mam "+intersects[ 0 ].object.name);
            }
            //dodawanie punktu w trybie edycji			
            if (adding == true) {
                var intersects = raycaster.intersectObjects([scene.getObjectByName('sfera')]);
                if (intersects.length > 0) {
                    //wczytajPano(intersects[ 0 ].object.name);
                    var px = intersects[0].point.x;
                    var py = intersects[0].point.y;
                    var pz = intersects[0].point.z;
                    //console.log('px:'+px+' py:'+py+' pz:'+pz)
                    //var lan=Math.atan(py/px) * 180 / Math.PI
                    //var lon=Math.asin(pz/80) * 180 / Math.PI
                    //console.log('lan:'+lan+'  lon:'+lon);
                    if (pointToAdd != 'info') {
                        //wybMin.style.border = '0px solid #E8272C';
                        wybMin.style.boxShadow = "";
                        //edW.style.display='none';
                    } else {
                        pointToAddName = edWitxt.value;
                    }

                    addPoint(pointToAdd, pointToAddName, pointToAddID, px, py, pz);

                    pointToAdd = '';
                    adding = false;
                    panoDiv.style.cursor = 'default';
                    edWi.style.display = 'none';
                    edWitxt.value = '';
                    //alert("mam "+intersects[ 0 ].point.x);
                }
            }
            ////przeliczanie wetkorów kliknięć dla pomiarów
            if (pomiar == true) {
                var ray = new THREE.Raycaster();
                ray.setFromCamera(mouse, camera);
                var inters = raycaster.intersectObjects([pomiar_plane]); //scene.getObjectByName('sfera')]);//
                if (inters.length > 0) {

                    pomiar_punkty.push(inters[0].point);

                    //alert('trafiono'+pomiar_punkty.length);


                }
                if (pomiar_punkty.length > 1) {
                    var material = new THREE.LineBasicMaterial({
                        color: 0xff0000,
                        linewidth: 6
                    });
                    material.depthTest = false;
                    material.depthWrite = false;
                    var geometry = new THREE.Geometry();
                    geometry.vertices = pomiar_punkty.concat([]);

                    var line = new THREE.Line(geometry, material);
                    line.name = 'pomiar';
                    scene.remove(scene.getObjectByName('pomiar'));
                    while (scene.getObjectByName('dystans') != null) {
                        scene.remove(scene.getObjectByName('dystans'));
                    }

                    scene.add(line);

                    for (var i = 1; i < geometry.vertices.length; i++) {

                        rysujDystans(geometry.vertices[i - 1], geometry.vertices[i]);
                    }

                    //console.log(geometry.vertices)
                }
            }

        }
        
        
        /**
         * Rejestrowanie ruchu myszy
         * 
         * @param {type} event
         */
        function onDocumentMouseMove(event) {
			var rect = panoDiv.getBoundingClientRect();
            if (manualControl) {
                //longitude = (savedX - event.clientX) * 0.1 + savedLongitude;
                //latitude = (event.clientY - savedY) * 0.1 + savedLatitude;
				if(event.touches!=undefined){
					if ( event.touches.length === 1 ) {
						event.preventDefault();
						//ruchX = -(savedX - event.touches[ 0 ].pageX) * 0.01;
						//ruchY = -(event.touches[ 0 ].pageY - savedY) * 0.01;
						longitude +=(savedX - event.touches[ 0 ].pageX) * 0.301;
						latitude +=(event.touches[ 0 ].pageY - savedY) * 0.301;
						ruchX = (savedX - event.touches[ 0 ].pageX) * 0.05;
						ruchY = (event.touches[ 0 ].pageY - savedY) * 0.05;
						savedX = event.touches[ 0 ].pageX;
						savedY = event.touches[ 0 ].pageY;
						}
					}else{
					ruchX = -(savedX - event.clientX) * 0.01;
					ruchY = -(event.clientY - savedY) * 0.01;
					
					}
                //longitude -= (savedX - event.clientX) * 0.002;
                //latitude -= (event.clientY - savedY) * 0.002 ;
		
            } else {

                if(event.touches!=undefined){
					if ( event.touches.length === 1 ) {
						event.preventDefault();
						mouse.x = ((event.touches[ 0 ].pageX - rect.left) / renderer.domElement.clientWidth) * 2 - 1;//event.touches[ 0 ].pageX
						mouse.y = -((event.touches[ 0 ].pageY - rect.top) / renderer.domElement.clientHeight) * 2 + 1;
						}
					}else{
					mouse.x = ((event.clientX - rect.left) / renderer.domElement.clientWidth) * 2 - 1;
					mouse.y = -((event.clientY - rect.top) / renderer.domElement.clientHeight) * 2 + 1;
					}
                raycaster.setFromCamera(mouse, camera);
                var intersects = raycaster.intersectObjects(punkty);
                if (intersects.length > 0 && intersects[0].object.userData.UName != '') {
					//alert(intersects[ 0 ].object.userData.UName);
                    tooltip.innerHTML = intersects[0].object.userData.UName;
                    if (delMode == true)
                        tooltip.innerHTML = '<b style="color:red;">Usuń punkt:</b><br>' + intersects[0].object.userData.UName;
                    tooltip.style.top = event.clientY - rect.top + 15 + 'px';
                    tooltip.style.left = event.clientX - rect.left + 10 + 'px';
                    tooltip.style.display = '';
                } else {
                    tooltip.innerHTML = '';
                    tooltip.style.top = 0 + 'px';
                    tooltip.style.left = 0 + 'px';
                    tooltip.style.display = 'none';
                }

            }
            if (pomiar == true) {
                var ray = new THREE.Raycaster();
                ray.setFromCamera(mouse, camera);
                var inters = raycaster.intersectObjects([pomiar_plane]); //scene.getObjectByName('sfera')]);//
                if (inters.length > 0) {
                    if (pomiar_punkty.length > 0) {
                        var material = new THREE.LineBasicMaterial({
                            color: 0xff0000,
                            linewidth: 6
                        });
                        material.depthTest = false;
                        material.depthWrite = false;
                        var geometry = new THREE.Geometry();
                        geometry.vertices = [pomiar_punkty[pomiar_punkty.length - 1], inters[0].point]; //pomiar_punkty.concat([inters[0].point]);
                        //geometry.vertices.push(inters[0].point);
                        var line = new THREE.Line(geometry, material);
                        scene.remove(scene.getObjectByName('pomiar_bierzacy'));
                        line.name = 'pomiar_bierzacy';
                        scene.remove(scene.getObjectByName('dystans_bierzacy'));
                        rysujDystans(geometry.vertices[0], geometry.vertices[1], true);

                        scene.add(line);
                        //console.log(geometry.vertices)
                    }
                }
            }

        }
        
        
        /**
         * Wyłaczanie ruchu po puszczeniu przycisku myszy
         * 
         * @param {type} event
         */
        function onDocumentMouseUp(event) {
            panoDiv.style.cursor = 'default';
            manualControl = false;
			tochMove=false;
        }

        /**
         * Zoom na scrollu
         * 
         * @param {type} event
         */	
        function onDocumentMouseWheel(event) {
            event.preventDefault();
			// WebKit
            if (event.wheelDeltaY) {
                camera.fov -= event.wheelDeltaY * 0.05;
                // Opera / Explorer 9
            } else if (event.wheelDelta) {
                camera.fov -= event.wheelDelta * 0.05;
                // Firefox
            } else if (event.detail) {
                camera.fov -= event.detail * (-1.0);
            }
            if (camera.fov < 30)
                camera.fov = 30;
            if (camera.fov > 140)
                camera.fov = 140;
            camera.updateProjectionMatrix();

        }
        
        switch(settings.typ) {
            case 'pano':
            default:
                init_pano(this.attr("id"), settings.xml, settings.IDxml, settings.tryb);
            break;
            case 'mov':
                init_mov(this.attr("id"), settings.link);
            break;
        }
 
    };
 
}( jQuery ));
