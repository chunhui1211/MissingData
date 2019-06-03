<!doctype html>
<html lang="en">
  <head>
    <title>MissingData</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css" />
  </head>
  
  <body>
<div class="container-fluid">
    <div class="row rowheader" >
        <div class="col">
           <h1><a href='http://localhost/Missingdata/index.php'>Missing Data</a></h1>
        </div>
    </div>
    </div>
    <div class="container">  
    <div class="row mt-5">
        <?php
            session_start();
            $new_name=$_SESSION['new_name'];
            set_time_limit(0)
    
          ?> 
        <div class="col-4">
        <button type="button" class="btn btn-warning"  onclick="location.href='downloadfile.php?file=<?=$new_name?>'"><i class="fas fa-download mr-2"></i>檔案匯出</button>
        <br/><br/>
        <?php
        
        if ($_SESSION['list']!=null) {
          for($i=0;$i<count($_SESSION['list']);$i++)
          {
            $value=enmethodtoch($_SESSION['list'][$i][1]);
            echo '步驟'.($i+1).'=>欄位:'.$_SESSION['list'][$i][0].',方法:'.$value."<br/>";
          }
        }
        function enmethodtoch($method)
        {
            if ($method=="del") {
                return "列表刪除";
            } elseif ($method=="delrow") {
                return "欄位刪除";
            } elseif ($method=="mean") {
                return "平均值";
            } elseif ($method=="mode") {
                return "眾值";
            } elseif ($method=="knn") {
                return "最近鄰居法";
            } elseif ($method=="linear") {
                return "線性迴歸法";
            } elseif ($method=="logistic") {
                return "邏輯迴歸法";
            }elseif ($method=="mice") {
              return "多重插補法";
          }
        }
        ?>       
        </div>
        <div class="col">    
        <h4><strong>檔案名稱</strong></h4>
        <p><?=$new_name?></p>       
        <hr>
        <form id="form1" action="imputation.php" method="post" enctype="multipart/form-data">
        <h4><strong>遺漏欄位</strong></h4>
        <small>S:文字 N:數字</small>
        <div id="cage">
        <?php   
        if($_SESSION["col_cage"]==null && $_SESSION["col_num"]==null)
        {
          header("Location: http://localhost/Missingdata/download.php");   
          exit;
        }
        if($_SESSION["col_cage"]!=null)
        {
          for ($i = 0 ; $i < count($_SESSION['col_cage']) ; $i++)
          {
            echo "<input type='radio' name='colname[]' id='{$_SESSION['col_cage'][$i]}' value='{$_SESSION['col_cage'][$i]}'>";
            echo "<label for='{$_SESSION['col_cage'][$i]}'>{$_SESSION['col_cage'][$i]} (S)</label><br/>";
          }
        }
                        
        ?>
        </div>
        <div id="num">
        <?php
        if($_SESSION["col_num"]!=null)
        {
          for ($i = 0 ; $i < count($_SESSION['col_num']) ; $i++)
          {
            echo "<input type='radio' name='colname[]' id='{$_SESSION['col_num'][$i]}' value='{$_SESSION['col_num'][$i]}'>";
            echo "<label for='{$_SESSION['col_num'][$i]}'>{$_SESSION['col_num'][$i]} (N)</label><br/>";
          } 
        }         
        ?>
        </div>
        <hr>
        <h4><strong>填補方法</strong></h4>
        <strong>刪除法</strong>
        <input type="checkbox" name="method[del]" value="del" id="del" class="method"/>
        <label for="del">列表刪除</label>
        <input type="checkbox" name="method[delrow]" value="delrow" id="delrow" class="method"/>
        <label for="delrow">欄位刪除</label>
        <br/><br/>
        <strong>補值法</strong>    
        <input type="checkbox" name="method[mean]" value="mean" id="mean" class="method"/>
        <label for="mean">平均值</label>
        <input type="checkbox" name="method[mode]" value="mode" id="mode" class="method"/>
        <label for="mode">眾值</label>
        <input type="checkbox" name="method[knn]" value="knn" id="knn" class="method"/>
        <label for="knn">最近鄰居法</label>
        <input type="checkbox" name="method[linear]" value="linear" id="linear" class="method"/>
        <label for="linear">線性迴歸法</label>
        <input type="checkbox" name="method[logistic]" value="logistic" id="logistic" class="method"/>
        <label for="logistic" >邏輯迴歸法</label>
        <input type="checkbox" name="method[mice]" value="mice" id="mice" class="method"/>
        <label for="mice" >多重插補法</label>
        <hr>
        <h4><strong>視覺化圖表</strong></h4>
        <input type="checkbox" name="vp[bar]" class="vp" id="bar" disabled>
        <label  for="bar">數值長條圖</label>
        <input type="checkbox"  name="vp[cabar]" class="vp" id="cabar" disabled>
        <label  for="cabar">文字長條圖</label>
        <input type="checkbox"  name="vp[pie]" class="vp" id="pie" disabled>
        <label  for="pie">圓餅圖</label>
        <input type="checkbox"  name="vp[box]" class="vp" id="box" disabled>
        <label  for="box">盒狀圖</label>
        <input type="checkbox"  name="vp[joint]" class="vp" id="joint" disabled>
        <label  for="joint">散點圖</label>
        <select id="select" name='ycol' class="custom-select" multiple></select>
        <hr>
        <button type="submit" class="btn btn-primary" name="submit" data-toggle="modal" data-target="#Modal"><i class="far fa-share-square mr-2"></i>送出</button>
        <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Loading...</h5>
                <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
                </div>
              </div>
            </div>
          </div>
        </form>
<br/><br/><br/>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function() {
      $('button[type="submit"]').click(function(){
        var colname=$('input[name="colname[]"]:checked').length;
        var method=$('input[class="method"]:checked').length;
        var vp=$('input[class="vp"]:checked').length;
        if(colname==0)
        {
          alert("請選擇欄位");
          return false;
        }
        else if(method==0)
        {
          alert("請選擇方法");
          return false; 
        }
        else if(vp==0)
        {
          alert("請選擇圖表樣式");
          return false; 
        }
        else
        {
          document.form1.submit();
          
        }
      });
      if ($('#cage input').is(':checked')) {      
          cage();
      }
      if ($('#num input').is(':checked')) {
          num();
      }
      $('#cage input').click(function(){
        cage();
      })
      $('#num input').click(function(){
        num();
      })

      $('input[name="colname[]"]').change(function(){
        $('#select').hide();
        $("#select option").remove();
        $('#joint').prop('checked', false);      
        option();
        $('#bar').attr("disabled", false);
        $('#box').attr("disabled", false);
        $('#joint').attr("disabled", false);
        $('#cabar').attr("disabled", false);
        $('#pie').attr("disabled", false);
      })
      
      $('#select').hide();
    
      $("#joint").change(function() {
        if(this.checked) 
        {
          $('#select').show();
              $('#select option').each(function() {     
                if($('input:radio:checked[name="colname[]"]').val()==this.value)
                  $(this).attr('disabled','disabled');
                })    
        }     
      })

      var cage=function(){
        $('label[for=mean],input#mean').hide();
        $('label[for=knn],input#knn').hide();
        $('label[for=linear],input#linear').hide();
        $('label[for=mode],input#mode').show();
        $('label[for=logistic],input#logistic').show();
        $('label[for=mice],input#mice').hide();
        $('label[for=cabar],input#cabar').show();
        $('label[for=pie],input#pie').show();
        $('label[for=bar],input#bar').hide();
        $('label[for=box],input#box').hide();
        $('label[for=joint],input#joint').hide();
      };
      var num=function(){
        $('label[for=logistic],input#logistic').hide();
        $('label[for=mean],input#mean').show();
        $('label[for=mode],input#mode').show();
        $('label[for=knn],input#knn').show();
        $('label[for=linear],input#linear').show();
        $('label[for=mice],input#mice').show();
        $('label[for=cabar],input#cabar').hide();
        $('label[for=pie],input#pie').show();
        $('label[for=bar],input#bar').show();
        $('label[for=box],input#box').show();
        $('label[for=joint],input#joint').show();
      };
      var option=function(){            
        var col_num = <?php echo json_encode($_SESSION['num']); ?>;
        $("#select").append($("<option></option>").text("請選擇Y軸").attr('disabled','disabled'));
        for(var i=0;i<col_num.length;i++)
        {          
          $("#select").append($("<option></option>").attr("value", col_num[i]).text(col_num[i]));
        }      
      };

    });
  </script>
  </body>
</html>
         