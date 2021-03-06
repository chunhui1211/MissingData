<!doctype html>
<html lang="en">

<head>
    <title>MissingData</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.css" />
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <div class="container-fluid">
        <div class="row rowheader">
            <div class="col">
                <h1><a href='http://localhost/Missingdata/index.php'>Missing Data</a></h1>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <?php
        session_start();
        $new_name = $_SESSION['new_name'];
        echo "<p>檔案名稱:" . $new_name . "</p>";
        echo "<p>預覽次數:" . $_SESSION['count'] . "</p>";
        echo "<p>填補欄位:" . $_SESSION['col'] . "</p>";
        $method = $_SESSION['method'];
        $method_array = array();
        if (is_array($_SESSION['col_cage'])) {
            foreach ($_SESSION['col_cage'] as  $value) {
                if ($value == $_SESSION['col']) {
                    $method_array = array("del", "mode", "logistic");
                }
            }
        }
        if (is_array($_SESSION['col_num'])) {
            foreach ($_SESSION['col_num'] as  $value) {
                if ($value == $_SESSION['col']) {
                    $method_array = array("del", "mean", "mode", "knn", "linear","mice");
                } 
            }
        }
        echo "<p>填補方法:";
        foreach ($_SESSION['method'] as $key => $value) {
            echo enmethodtoch($value) . ';';
        }
        echo "</p>";

        function enmethodtoch($method)
        {
            if ($method == "del") {
                return "列表刪除";
            } elseif ($method == "mean") {
                return "平均值";
            } elseif ($method == "mode") {
                return "眾值";
            } elseif ($method == "knn") {
                return "最近鄰居法";
            } elseif ($method == "linear") {
                return "線性迴歸法";
            } elseif ($method == "logistic") {
                return "邏輯迴歸法";
            } elseif ($method == "mice") {
                return "多重插補法";
            }
        }

        ?>
        <form action="imputation.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-4 mb-2">
                    <p>填補方法設定:</p>
                    <select class="custom-select" name="option">
                        <?php
                        foreach ($method_array as $key => $value) {
                            $s = enmethodtoch($value);
                            echo "<option value='{$value}'>{$s}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-1 mt-5">
                    <button type="button" class="btn btn-secondary" onclick="location.href='http://localhost/Missingdata/mechanisms.php'"><i class="fas fa-undo mr-2"></i>返回修改</button>
                </div>
                <div class="col-1 mt-5">
                    <button type="submit" name="submit" value="check" class="btn btn-primary" data-toggle="modal" data-target="#Modal"><i class="far fa-check-circle mr-2"></i>確定填補</button>
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
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col">
                <h1>視覺化圖表</h1>
                <nav>
                    <div class="nav nav-tabs " id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="factor" data-toggle="tab" href="#nav-factor">長條圖</a>
                        <a class="nav-item nav-link " id="cabar" data-toggle="tab" href="#nav-cabar">直方圖</a>
                        <a class="nav-item nav-link " id="pie" data-toggle="tab" href="#nav-pie">圓餅圖</a>
                        <a class="nav-item nav-link " id="box" data-toggle="tab" href="#nav-box">盒狀圖</a>
                        <!-- <a class="nav-item nav-link " id="dist" data-toggle="tab" href="#nav-dist">密度圖</a> -->
                        <a class="nav-item nav-link" id="joint" data-toggle="tab" href="#nav-joint">散點圖</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <?php
                    function displayimg($type)
                    {
                        $new_name = $_SESSION['new_name'];
                        $new_name = substr($new_name, 0, -4);
                        $handle = opendir('./imputation_photo/' . $new_name . '/');
                        while (false !== ($file = readdir($handle))) {
                            list($filesname, $kzm) = explode(".", $file);
                            if ($kzm == "png" and strpos($filesname, $type) !== false) {
                                if (
                                    !is_dir('./' . $file) and
                                    substr($file, 0, strlen($_SESSION['count'])) == $_SESSION['count'] and
                                    substr($file, strlen($_SESSION['count']), strlen($_SESSION['col'])) == $_SESSION['col']
                                ) {
                                    $array[] = $file;
                                }
                            }
                        }
                        if (isset($array)) {
                            for ($j = 0; $j < count($array); $j++) {
                                echo  "<a href=\"imputation_photo/$new_name/$array[$j]\" class=\"fancybox\">";
                                echo "<img class=\"$type\" src=\"./imputation_photo/$new_name/$array[$j]\"></a>";
                            }
                        }
                    }
                    ?>
                    <div class="tab-pane fade show active" id="nav-factor" role="tabpanel" aria-labelledby="nav-home-tab">
                        <?php
                        displayimg("factor");
                        ?>
                    </div>
                    <div class="tab-pane fade" id="nav-cabar" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <?php
                        displayimg("cabar");
                        ?>
                    </div>
                    <div class="tab-pane fade" id="nav-pie" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <small>此樣式圓餅圖將列出比例較高五筆資料</small><br>
                        <?php
                        
                        displayimg("pie");
                        ?>
                    </div>
                    <div class="tab-pane fade" id="nav-box" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <?php
                        displayimg("box");
                        ?>
                    </div>
                    <div class="tab-pane fade" id="nav-dist" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <?php
                        displayimg("dist");
                        ?>
                    </div>
                    <div class="tab-pane fade" id="nav-joint" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <?php
                        displayimg("joint");
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <h1>資料內容</h1>
        <div class="row mt-2">
            <div class="col-1">
                <?php
                require_once "C:/xampp/htdocs/Missingdata/PHPExcel/Classes/PHPExcel.php";
                $excelObj = PHPExcel_IOFactory::load("upload/" . $_SESSION['new_name']);
                $worksheet = $excelObj->getSheet(0);
                $toCol = $worksheet->getHighestColumn();
                $toCol++;
                $var = $_SESSION['col'];

                echo '<table>';
                echo '<tr>';
                echo "原始資料";
                echo '</tr>';
                for ($col = "A"; $col != $toCol; $col++) {
                    if ($var == $worksheet->getCell($col . 1)->getValue()) {

                        for ($row = 1; $row <= $worksheet->getHighestRow(); $row++) {
                            if ($worksheet->getCell($col . $row)->getValue() === null) {
                                echo '<tr>';
                                echo "<td>";
                                echo "?";
                                echo "</td>";
                                echo '</tr>';
                            } else {
                                echo '<tr>';
                                echo "<td>";
                                echo $worksheet->getCell($col . $row)->getValue();
                                echo "</td>";
                                echo '</tr>';
                            }
                        }
                    }
                }
                echo '</table>';
                ?>
            </div>
            <!-- <ul class="nav nav-tabs">             -->
            <?php
            for ($i = 0; $i < count($method); $i++) {
                $opname = $_SESSION['count'] . $_SESSION['col'] . $method[$i] . '_' . $new_name;
                $opname = explode(".", $opname);
                if ($i == 0) {
                    echo "<div class='col-1'>";
                    $m = enmethodtoch($method[$i]);
                    displayfile($opname, $m);
                    echo "</div>";
                } else {
                    echo "<div class='col-1'>";

                    $m = enmethodtoch($method[$i]);
                    displayfile($opname, $m);
                    echo "</div>";
                }
            }
            ?>

            <?php
            function displayfile($opname, $m)
            {
                require_once "C:/xampp/htdocs/Missingdata/PHPExcel/Classes/PHPExcel.php";
                $excelObj = PHPExcel_IOFactory::load("download/" . $opname[0] . '.' . $opname[1]);
                $worksheet = $excelObj->getSheet(0);
                $toCol = $worksheet->getHighestColumn();
                $toCol++;
                $var = $_SESSION['col'];

                echo '<table>';
                echo '<tr>';
                echo $m;
                echo '</tr>';
                for ($col = "A"; $col != $toCol; $col++) {
                    if ($var == $worksheet->getCell($col . 1)->getValue()) {

                        for ($row = 1; $row <= $worksheet->getHighestRow(); $row++) {
                            echo '<tr>';
                            echo "<td>";
                            echo $worksheet->getCell($col . $row)->getValue();
                            echo "</td>";
                            echo '</tr>';
                        }
                    }
                }
                echo '</table>';
            }
            ?>
            <!-- </div> -->
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
        <!-- jQuery v1.9.1 -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
        <!-- fancyBox v2.1.5 -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
</body>
<script>
    $('.fancybox').fancybox();
</script>
<script>
    $(function() {

        if ($("img[class='factor']").length > 0) {
            $("#factor").append($("<span>").addClass("badge badge-danger").text($("img[class='factor']").length));
        }
        if ($("img[class='cabar']").length > 0) {
            $("#cabar").append($("<span>").addClass("badge badge-danger").text($("img[class='cabar']").length));
        }
        if ($("img[class='pie']").length > 0) {
            $("#pie").append($("<span>").addClass("badge badge-danger").text($("img[class='pie']").length));
        }
        if ($("img[class='box']").length > 0) {
            $("#box").append($("<span>").addClass("badge badge-danger").text($("img[class='box']").length));
        }
        if ($("img[class='joint']").length > 0) {
            $("#joint").append($("<span>").addClass("badge badge-danger").text($("img[class='joint']").length));
        }
        if ($("img[class='dist']").length > 0) {
            $("#dist").append($("<span>").addClass("badge badge-danger").text($("img[class='dist']").length));
        }
    });
</script>
</body>

</html>