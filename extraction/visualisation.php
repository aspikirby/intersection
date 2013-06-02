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
            
            var width = 800; 
            var height = 500;       
            var nbrNode = 20 ; 
            var rayonNode = (((height /nbrNode ) -1 ) /3);
            var svg = d3.select("#output").append("svg").attr("width", width).attr("height", height); 
            /*fonction d'echelle pour avoir un rayon en fonction des amis=*/
            var scaleRayonAmiSmall = d3.scale.linear()
                    .domain([0, 140])
                    .range([5, 10]);
                    
            var scaleRayonAmiBig = d3.scale.linear()
                    .domain([140, 5000])
                    .range([10, 30]);   
            
    
            function rayonAmi(nbrAmi) { if ( nbrAmi >= 140) {return scaleRayonAmiSmall(nbrAmi);}else {return scaleRayonAmiBig (nbrAmi);}};
            

            function color (varGender)  { if (varGender == "female") {return "pink";}else {return "blue"; }} ; 
            

            
            
            /*d3.json("https://graph.facebook.com/559011579/friends?access_token=CAAET2OJzlxwBAErCE4SxABYsHTyshi5YxuekbH3KpF4w9qW9KyTksgcvB5lK4Nz9zUDn13FdsuYmG1VJdrMhvviK5zRaDtNv9ZAvkzPyTXPFXpgy0XcpJkzXlHiqMwhyGjmMhT0EyhaZBT79w3E2S9gfnNop0ZD", function (data) {
            */
            
            
            /*-------creation du node de la source------- */
            
            d3.json('<?php echo sprintf('formated_data.json.php?fb_user_1=%s&fb_user_2=1%s', $_GET['fb_user_1'], $_GET['fb_user_2']); ?>', function (data) {
            var k= 0 ; /*compteur de passage */
            var cheminGenre = data.user1.gender ; //chemin vers le genre de l'ami
            var cheminAmi = data.user1.data;
            
            
                /*creation de la fonction pour afficher la liste des amis */
            function affichageAmi() { 
            if ( k==0 )
                {
                    svg.append("g")
                        .attr("class","amisource")
                        .selectAll(".amisource")
                        .data(cheminAmi)
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

            
            /*creation du node */
            svg.append("circle")
                .attr("cx",width*1/4)
                .attr("cy",height*1/2)
                .attr("r",rayonNode)
                .attr("fill",color(cheminGenre)); 
                
                
            
                
            /*creation du node relatif au amis et du lien */
            
            svg.append("circle")
                .attr("cx",width*1/8)
                .attr("cy",height*1/2)
                .attr("r",rayonAmi(cheminAmi.length))
                .attr("fill","red")
                .on("click",affichageAmi);
                
            
                
            svg.append("text")
                .attr("x",width*1/8-rayonAmi(cheminAmi.length))
                .attr("y",height*1/2-rayonAmi(cheminAmi.length))
                .text(function (d) {return "Nombre d'ami: "+cheminAmi.length;})
            
            svg.append("line")  
                .attr("x1",width*1/4) //source
                .attr("y1",height*1/2)
                .attr("x2",width*1/8)  //cible 
                .attr("y2",height*1/2)
                .attr("stroke","grey"); //couleur
                
    
            
            });
            
            /*----------------creation du node de la cible -------------------------*/
            
            d3.json('<?php echo sprintf('formated_data.json.php?fb_user_1=%s&fb_user_2=1%s', $_GET['fb_user_1'], $_GET['fb_user_2']); ?>', function (data){
            var cheminGenre = data.user2.gender;
            var cheminAmi = data.user2.data;
            var j= 0 ; /*compteur de passage */
            
                /*creation de la fonction pour afficher la liste des amis */
            function affichageAmi() { 
            if ( j==0 )
                {
                    svg.append("g")
                        .attr("class","amicible")
                        .selectAll(".amicible")
                        .data(cheminAmi)
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
            
            svg.append("circle")
                .attr("cx",width*3/4)
                .attr("cy",height*1/2)
                .attr("r",rayonNode)
                .attr("fill",color(cheminGenre))
                .attr("stroke","grey");
            
            /*creation du node relatif au amis et du lien */
            
            svg.append("circle")
                .attr("cx",width*7/8)
                .attr("cy",height*1/2)
                .attr("r",rayonAmi(cheminAmi.length))
                .attr("fill","red")
                .on ("mousedown", affichageAmi);/*affichage de la liste d'amis sur un clic */
                
                
                /*affichage du nombre d'amis */
            svg.append("text")
                .attr("x",width*7/8-rayonAmi(cheminAmi.length))
                .attr("y",height*1/2-rayonAmi(cheminAmi.length))
                .text(function (d) {return "Nombre d'ami: "+cheminAmi.length;});
                
                
            svg.append("line")  
                .attr("x1",width*3/4) //source
                .attr("y1",height*1/2)
                .attr("x2",width*7/8)  //cible 
                .attr("y2",height*1/2)
                .attr("stroke","grey"); //couleur   
                
        
            
            });

            /*--------- creation des nodes des amis en communs --------------------*/
            /*
            d3.json("data.json", function (data){
            var cheminAmisCommun = data.schema[2].ami; 
            //node
            svg.selectAll()
                .data(cheminAmisCommun)
                .enter()
                .append("circle")
                .attr("cy",function (d,i) {return rayonNode + i*  3 *rayonNode; })
                .attr("cx",width*1/2)
                .attr("fill","green") 
                .attr("r",rayonNode);
            
            
            // lien node - source 
            svg.selectAll()
                .data(cheminAmisCommun)
                .enter()
                .append("line")
                .attr("y1",function (d,i) {return rayonNode + i*  3*rayonNode; }) //point 1 , le node nouvellement crée 
                .attr("x1",width *1/2 )
                .attr("y2", height * 1/2 ) //point 2 la source
                .attr("x2", width * 1/4 ) 
                .attr("stroke","grey"); 
            
            
            
            // lien node - cible
            svg.selectAll()
                .data(cheminAmisCommun)
                .enter()
                .append("line")
                .attr("y1",function (d,i) {return rayonNode + i*  3*rayonNode; }) //point 1 , le node nouvellement crée 
                .attr("x1",width *1/2 )
                .attr("y2", height * 1/2 ) //point 2 la cible
                .attr("x2", width * 3/4) 
                .attr("stroke","grey"); 
            });*/

        </script>

        <?php endif; ?>
    </body>
</html>