<!DOCTYPE html>

<?php
$output = false;
if(isset($_GET['fb_user_1']) && isset($_GET['fb_user_2'])) {
    $output = true;
}
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="chosen/chosen.css" />
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="chosen/chosen.jquery.js" type="text/javascript"></script>
        <title>Social graph</title>
    </head>
    <body>
        <aside>
            <div class="title">
                <h1>INTERSECTION</h1>
                <h2>Facebook cross-visualization graph</h2>
            </div>
            <form action="visualisation.php" method="get">
                <fieldsetrr>
                    <label>Enter a name</label>
                    <select name="fb_user_1" class="chzn-autocomplete"></select>
                </fieldset>
                <fieldset>
                    <label>Enter a name</label>
                    <select name="fb_user_2" class="chzn-autocomplete"></select>
                </fieldset>
                <input class="btn btn-inverse btn-large" type="submit" value="Visualize" />
            </form>
            <script type="text/javascript">
                $('document').ready(function() {

                    $.getJSON("listuser.json.php")
                    .done(function(data) {
                        var items = '<option></option>';

                        $.each(data, function(key, val) {
                            items += '<option value="' + key + '">' + val + '</option>';
                        });

                        $('.chzn-autocomplete').append(items);
                        $('.chzn-autocomplete').chosen();
                    })
                    .fail(function(jqxhr, textStatus, error) {
                        var err = textStatus + ', ' + error;
                        console.log( "Request Failed: " + err);
                    });
                });
            </script>
        </aside>

        <div id="output">
        </div>
        <?php if($output): ?>

        <script type="text/javascript" src="d3.v3.js"></script>
        <script type="text/javascript">
            
/*-------------variables de configuration--------------------*/

            var width = 800; //taille du svg 
            var height = 500;       
            var nbrNode = 20 ; //nombre de node affichage a l'ecran 
            var rayonNode = (((height /nbrNode ) -1 ) /3); //rayon des nodes automatiquement generé
            var svg = d3.select("#output").append("svg").attr("width", width).attr("height", height); //creation du canevas
            /*fonction d'echelle pour avoir un rayon en fonction des amis=*/
            var scaleRayonAmiSmall = d3.scale.linear()
                    .domain([0, 140])
                    .range([5, 10]);
                    
            var scaleRayonAmiBig = d3.scale.linear()
                    .domain([140, 5000])
                    .range([10, 30]);   
                
            function rayonAmi(nbrAmi) { if ( nbrAmi >= 140) {return scaleRayonAmiSmall(nbrAmi);}else {return scaleRayonAmiBig (nbrAmi);}};
            
	    /*fonction de generation de couleur en fonction du genre*/
            function color (varGender)  { if (varGender == "female") {return "pink";}else {return "blue"; }} ; 
            
/*-------------fonction d'integration du json sur la page--------------------*/

d3.json('<?php echo sprintf('formated_data.json.php?fb_user_1=%s&fb_user_2=%s', $_GET['fb_user_1'], $_GET['fb_user_2']); ?>', function (data) {
            
/*-------------variables de chemin-----------------------------------------*/
	
		var cheminGenreSource = data.user1.gender ; //chemin vers le genre de l'ami source
		var cheminAmiSource = data.user1.data; // chemin vers le tableau d'ami de la source
		var cheminDonnéePersoSource = data.user1; // chemin vers les données perso de la source
		var cheminGenreCible = data.user2.gender; // chemin vers le genre de la cible 
		var cheminAmiCible = data.user2.data;// chemin vers le tableau d'ami de la cible
		var cheminDonnéePersoCible = data.user2; // chemin vers les données perso de la source
		var cheminAmisCommun = data.common; // chemin vers le tableau d'amis communs
            	
            
/*----------creation de la fonction pour afficher la liste des amis---------- */

		var k= 0 ; /*compteur de passage */
	/*affichage de la liste des  amis de la source*/
        
		function affichageAmiSource() { 
           if ( k==0 )
                {
                   svg.append("g")
                        .attr("class","amisource")
                      .selectAll(".amisource")
                       .data(cheminAmiSource)
                       .enter()
                        .append("text")
                        .attr("x", 10)
                        .attr("y",function (d,i) {return rayonNode + i*  3 *rayonNode; })
                        .text(function(d) {return d.name });
                    k++;
                }
            
            else
                {
                    d3.selectAll(".amisource")
                        .remove();
                    k--;
                }
                
            };

          /*affichage de la liste des  amis de la source*/
            function affichageAmiCible() { 
            if ( j==0 )
                {
                    svg.append("g")
                        .attr("class","amicible")
                        .selectAll(".amicible")
                        .data(cheminAmiCible)
                        .enter()
                        .append("text")
                        .attr("x", width-100)
                        .attr("y",function (d,i) {return rayonNode + i*  3 *rayonNode; })
                        .text(function(d) {return d.name });
                    j++;
                }
            
            else
                {
                    d3.selectAll(".amicible")
                        .remove();
                    j--;
                }
                
            };

 /*-------------------creation du node de la source--------------- */
            
           	 /*creation du node */
	svg.append("circle")
                .attr("cx",width*1/4)
                .attr("cy",height*1/2)
                .attr("r",rayonNode)
                .attr("fill",color(cheminGenreSource)); 
                
			/*ajout du nom au node crée */
			
	svg.append("text")
		.attr("x",width*1/4 - rayonNode)
		.attr("y",height*1/2+ 2*rayonNode)
		.text(function(d) {return cheminDonnéePersoSource.name});		
                 
          	/*creation du node relatif au amis et du lien */
            
	svg.append("circle")
                .attr("cx",width*1/8)
                .attr("cy",height*1/2)
                .attr("r",rayonAmi(cheminAmiSource.length))
                .attr("fill","red")
                .on("click",affichageAmiSource);
  
	svg.append("text")
                .attr("x",width*1/8-rayonAmi(cheminAmiSource.length))
                .attr("y",height*1/2-rayonAmi(cheminAmiSource.length))
                .text(function (d) {return "Nombre d'ami: "+cheminAmiSource.length;})
            
	svg.append("line")  
                .attr("x1",width*1/4) //source
                .attr("y1",height*1/2)
                .attr("x2",width*1/8)  //cible 
                .attr("y2",height*1/2)
                .attr("stroke","grey"); //couleur

 /*----------------creation du node de la cible -------------------------*/
  
		var j= 0 ; /*compteur de passage */
 
		/*creation du node*/
			
	svg.append("circle")
                .attr("cx",width*3/4)
                .attr("cy",height*1/2)
                .attr("r",rayonNode)
                .attr("fill",color(cheminGenreCible))
                .attr("stroke","grey");
			
	svg.append("text")
				.attr("x",width*3/4- rayonNode)
				.attr("y",height*1/2+ 2*rayonNode)
				.text(function(d) {return cheminDonnéePersoCible.name});	

		/*creation du node relatif au amis et du lien */
            
	svg.append("circle")
                .attr("cx",width*7/8)
                .attr("cy",height*1/2)
                .attr("r",rayonAmi(cheminAmiCible.length))
                .attr("fill","red")
                .on ("mousedown", affichageAmiCible);/*affichage de la liste d'amis sur un clic */
                
		/*affichage du nombre d'amis */
		
	svg.append("text")
                .attr("x",width*7/8-rayonAmi(cheminAmiCible.length)-10)
                .attr("y",height*1/2-rayonAmi(cheminAmiCible.length))
                .text(function (d) {return "Nombre d'ami: "+cheminAmiCible.length;});
                
        /*affichage du lien */
				
	svg.append("line")  
                .attr("x1",width*3/4) //source
                .attr("y1",height*1/2)
                .attr("x2",width*7/8)  //cible 
                .attr("y2",height*1/2)
                .attr("stroke","grey"); //couleur   

/*----------------creation des nodes des amis communs -------------------------*/
        
	/*creation des nodes*/
	
	svg.selectAll()
                .data(cheminAmisCommun)
                .enter()
                .append("circle")
                .attr("cy",function (d,i) {  if (i%2 == 0 ){return  height/2+ rayonNode/2 +( 3*i/2*rayonNode); }else {return height/2-rayonNode/2 -( 3*i/2 *rayonNode);}})
                .attr("cx",width*1/2)
                .attr("fill","orange") 
                .attr("r",rayonNode);
            
    /*creation des liens node - source*/
	
	svg.selectAll()
                .data(cheminAmisCommun)
                .enter()
                .append("line")
                .attr("y1",function (d,i) {  if (i%2 == 0 ){return  height/2+ rayonNode/2 +( 3*i/2*rayonNode); }else {return height/2-rayonNode/2 -( 3*i/2 *rayonNode);}})
                .attr("x1",width *1/2 )// point 1 le node nouvelement crée
                .attr("y2", height * 1/2 ) //point 2 la source
                .attr("x2", width * 1/4 ) 
                .attr("stroke","grey"); 

	/*creation des liens node - cible*/
			
	svg.selectAll()
                .data(cheminAmisCommun)
                .enter()
                .append("line")
                .attr("y1",function (d,i) {  if (i%2 == 0 ){return  height/2+ rayonNode/2 +( 3*i/2*rayonNode); }else {return height/2-rayonNode/2 -( 3*i/2 *rayonNode);}})
                .attr("x1",width *1/2 ) //point 1 , le node nouvellement crée 
                .attr("y2", height * 1/2 ) //point 2 la cible
                .attr("x2", width * 3/4) 
                .attr("stroke","grey"); 

	/*creation du texte*/

	svg.selectAll()
                .data(cheminAmisCommun)
				.enter()
				.append("text")
				.attr("x",width*1/2 + rayonNode)
				.attr("y",function (d,i) {  if (i%2 == 0 ){return  height/2+ rayonNode/2 +( 3*i/2*rayonNode); }else {return height/2-rayonNode/2 -( 3*i/2 *rayonNode);}})
				.text(function(d) {return d.name});

	});	

        </script>

        <?php endif; ?>
    </body>
</html>
