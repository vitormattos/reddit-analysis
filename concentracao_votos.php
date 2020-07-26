<?php
$query = "	SELECT 	  A.id, A.body, B.MEDIA, B.DESVIO_PADRAO, (A.SCORE / B.DESVIO_PADRAO) PERCENT
            FROM 
                  (
                      SELECT    id, body, ABS(score) as SCORE
                      FROM      ".$_GET['link_id']."
                  ) A
            LEFT JOIN            
                  (
                      SELECT 		AVG(ABS(score)) AS MEDIA,
                                STD(ABS(score)) AS DESVIO_PADRAO							
                      FROM 		  ".$_GET['link_id']."
                  ) B
                              
            ON 			1 = 1";
//echo "<pre>".$query."</pre>";							

foreach($con->query($query) as $row) {
	if($row['PERCENT'] > 3){ 
		$concentracao_votos[$id] = $row['body'];
 		
	}
}	
?>

<div class="card">
  <div class="card-header">  
    <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="modal" data-target="#modalConcentracaoVotos"></i>
    <!-- <b>Destaque de votos</b> -->
    <?php
    if (!empty($concentracao_votos)) { 
      if(count($concentracao_votos) > 1){
        echo "<h4 class='card-title'>Os seguintes comentários se destacaram por obterem muito votos:</h4>";
      }else{
        echo "<h4 class='card-title'>O seguinte comentário se destacou por obter muitos votos:</h4>";
      }      
      foreach($concentracao_votos as $id => $value) { 
        if($value == "[removed];"){
          echo "O comentário foi removido pelo moderador por ser um possível gerador de conflito";
        }else{ ?>      
          <div id="#concentracao_votos<?php echo $id; ?>" >
            <div class="card-text">
              <div id="#brief_concentracao_votos<?php echo $id; ?>">
                <?php echo substr($value,0,115).'... ';?>
                <a  data-toggle="collapse" data-target="#concentracao_votos<?php echo $id; ?>" aria-expanded="false" aria-controls="collapseTwo">
                  <i class="fa fa-plus-square-o" onclick="document.getElementById('#brief_concentracao_votos<?php echo $id; ?>').style.color = 'transparent'";></i>
                  <i class="fa fa-minus-square-o" onclick="document.getElementById('#brief_concentracao_votos<?php echo $id; ?>').style.color = '#747373'";></i>                
                </a>
              </div>
            </div>
            <div id="concentracao_votos<?php echo $id; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#concentracao_votos<?php echo $id; ?>">
              <?php echo $value; ?>
            </div>
          </div>	
        <?php
        }
      }
    }else{
      echo "<p class='card-title'>A respostas etão bem distribuídas aos comentários</p>";
    }?>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalConcentracaoVotos" tabindex="-1" role="dialog" aria-labelledby="modalConcentracaoVotos" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalConcentracaoVotos">Concentração de votos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Identifica se algum comentário concentrou os votos da discussão. (Se está a 3 vezes a cima do desvio padrão de votos).
      </div>
    </div>
  </div>
</div>