<div class="card">
  <div class="card-header indicators">
    <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="modal" data-target="#modalMonopolisticBehavior"></i>    
    <!-- <b>Comportamento monopolista: </b> -->
      <?php
      $query = "SELECT			A.author, 
                            A.count_id, 
                            B.MEDIA, 
                            B.DESVIO_PADRAO, 
                            C.QTD_AUTORES,
                            A.count_id / B.DESVIO_PADRAO AS PERCENTAGE

                FROM 		(                        
                            SELECT 		author, count(distinct id) AS count_id 
                            FROM 		  ".$_GET['link_id']."
                            WHERE 		author <> '[deleted]'
                            GROUP BY 	author     
                            ORDER BY 	count_id desc
                        ) A

                LEFT JOIN 	
                        (
                            SELECT 		AVG(X.count_id) AS MEDIA,
                                      STD(X.count_id) AS DESVIO_PADRAO
                            FROM 		(
                                      SELECT 		author, COUNT(DISTINCT id) AS count_id
                                      FROM 		  ".$_GET['link_id']."
                                      WHERE 		author <> '[deleted]'
                                      GROUP BY 	author
                                    ) X
                        ) B

                ON			1 = 1            

                LEFT JOIN 	
                        (
                            SELECT 		count(distinct author) AS QTD_AUTORES
                            FROM 		  ".$_GET['link_id']."
                            WHERE 		link_id = author <> '[deleted]'
                        ) C 
                ON 			1 = 1";
      // echo "<pre>".$query_score."</pre>";							

      foreach($con->query($query) as $row) {
        if ($row['PERCENTAGE'] > 3) {
          $authors[] = $row['author'];
        }        
      }
      if (!empty($authors)) { 
        $count = count($authors);
        if(count($authors) > 1){
          echo "<h4 class='card-title'>".$count." <b>autores falaram mais que o restante.</b></h4>";
          echo "<input type='button' class='btn btn-primary' aria-hidden='true' data-toggle='modal' data-target='#modalAuthors' value='Veja quais são'/>";
        }else{
          echo "<h4 class='card-title'>".$count." <b>autor falou mais que o restante.</b></h4>";
          echo "<input type='button' class='btn btn-primary' aria-hidden='true' data-toggle='modal' data-target='#modalAuthors' value='Veja qual é'/>";
        }
        foreach ($authors as $id => $value) {
          $modal_authors .= "<div style='margin-left:40%'><img src='img/avatar_1.png' width='20px'>
                              <a href='https://www.reddit.com/user/".$value."/' target='_blank'>".$value."</a>
                            </div><br>";
        }
      }?>
      <br>      
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalMonopolisticBehavior" tabindex="-1" role="dialog" aria-labelledby="modalMonopolisticBehavior" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalMonopolisticBehavior">Comportamento monopolista</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Identifica se algum autor fez mais comentários do que o restante. (Se comentou 3 vezes a cima do desvio padrão de comentários).
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAuthors" tabindex="-1" role="dialog" aria-labelledby="modalAuthorsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalAnalysisInteractionLabel">Autores</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php echo $modal_authors;?>
      </div>
    </div>
  </div>
</div>