<?php
//データベースへの接続
 $dsn = 'データベース名';
 $user = 'ユーザー名';
 $password = 'パスワード';
 $pdo = new PDO($dsn,$user,$password);

 //テーブル作成
  $sql="CREATE TABLE mission"
  ."("
  ."id INT AUTO_INCREMENT PRIMARY KEY," //idはINT＝整数型
  ."name char(30)," //名前はchar＝固定長文字型15文字
  ."comment TEXT," //コメントはTEXT＝テキスト型
  ."password char(30),"
  ."date char(60)"
  .");";
  $stmt=$pdo -> query($sql);


//編集機能
//編集フォームに数字があって、編集パスあるなら
if(!empty($_POST['edit1']) and !empty($_POST['edi_pass']))
{
   $sql = 'SELECT*FROM mission';
   //テーブル作成チェック
   $result = $pdo -> query($sql);

      //foreachをを使用することで配列の値を変数$rowに取りだす
        foreach($result as $row){

                            //番号＝編集フォーム　かつ　パスワードが編集パスなら
	                         if(($row['id']==$_POST['edit1']) and ($row['password']==$_POST['edi_pass'])){
	                       //変数送信パス＝送信パス
                               $COM_pass==$row['password'];
                         
                 	       $EDIT2=$row['id'];  //編集モード(hidden)=$rowの数字部分
                               $COMMENT1=$row['name']; //変数名前＝$rowの名前

	                       $COMMENT2=$row['comment']; //変数コメント＝$rowのコメント部分

                             }//＝文 end
                             
       }//foreach end
    $result = $pdo -> query($sql);

           if($_POST['edi_pass']!=$row['password']){
               echo "パスワードが違います。";
             }//≠ end                            

}//編集機能 end
 

//削除機能
//削除番号があって、削除パスがあるなら
if(!empty($_POST['delete']) and !empty($_POST['del_pass']))
{
          $sql = 'SELECT*FROM mission';
          $result=$pdo->query($sql);
    foreach($result as $row) {
           //削除番号＝番号で、削除パス＝パスなら
           if($_POST['delete'] == $row['id'] and $_POST['del_pass'] == $row['password']) {
           $id=$_POST['delete'];
           $password=$_POST['del_pass'];
           $sql="delete from mission where id = '$id' and password = '$password'";

          }//＝文 end
    }//foreach end
           $result=$pdo->query($sql);

              //入力した削除パス≠その行のパス    
           if( $_POST['del_pass']!==$row['password'])
              {
                echo"パスワードが違います。";
               }//echo end
}//削除 end


//編集機能
//名前、コメント、パスワード、hiddenがあるなら
if(!empty($_POST['name']) and !empty($_POST['comment']) and !empty($_POST['password']) and !empty($_POST['edit2']))
{
 $id = $_POST['edit2'];
 $name = $_POST['name'];
 $comment = $_POST['comment'];
 $password = $_POST['password'];
 $sql = "update mission set name ='$name', comment = '$comment', password = '$password' where id = $id";
 $result = $pdo -> query($sql);
}//編集 end

//新規作成
if(empty($_POST['edit2'])) {//編集モードが空なら
         //投稿番号＋１（１からnまで）
         $next=intval($pdo->query("SELECT max(id) FROM mission")->fetchColumn());
         $id = ($next+1);
	 $name = $_POST['name'];
	 $comment = $_POST['comment'];
         $password = $_POST['password'];
	 $date = date('Y/m/d H:i:s');


    if((!empty($name))and(!empty($comment))and(empty($_POST['password'])))
        {echo"パスワードが入力されていません。";
      }//パスワードの有無 end   
       if((!empty($name))and(!empty($comment))and(!empty($_POST['password'])))    {
       	  //insertをおこなって、データ入力
 	  //bindParamの中身はテーブルの中身によって変化する
 	  $sql = $pdo->prepare("INSERT INTO mission(id,name,comment,password,date)VALUES(:id,:name,:comment,:password,:date)");
 	  $sql -> bindParam(':id',$id,PDO::PARAM_STR);
 	  $sql -> bindParam(':name',$name,PDO::PARAM_STR);
 	  $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
          $sql -> bindParam(':password',$password,PDO::PARAM_STR);
 	  $sql -> bindParam(':date',$date,PDO::PARAM_STR);

          $sql->execute();
    
         }

}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>mission_4-1</title>
  </head>
  <body> 
<form  method = "post" action="mission_4-1.php">
<p>
　　　名前：<input type = "text" name = "name" placeholder = "名前"
             value = "<?php echo $COMMENT1;?>"><br>
                      <!--$COMMENT1をフォームの変数とする-->

　コメント：<input type = "text" name = "comment"  placeholder = "コメント"
               value = "<?php echo $COMMENT2;?>"><br>
                        <!--$COMMENT2をフォームの変数とする-->

    <!--編集モード-->
         <input type="hidden" name="edit2"    
              value="<?php echo $EDIT2;?>">
                     <!--$EDIT2をフォームの変数とする-->
パスワード：<input type = "text" name = "password"  placeholder = "パスワード"               value = "<?php echo $COM_pass;?>">

	<input type = "submit" value = "送信">
</p>
</form>

<form  method = "post" action="mission_4-1.php">
<p>
　削除番号：<input type="text" name="delete" placeholder="削除対象番号"><br>
パスワード：<input type = "text" name = "del_pass"  placeholder = "パスワード">

	<input type = "submit"  value = "削除">
</p>
</form>

<form  method = "post" action="mission_4-1.php">
<p>
　編集番号：<input type="text" name="edit1" placeholder="編集対象番号"><br>
パスワード：<input type = "text" name = "edi_pass"  placeholder = "パスワード">

        <input type = "submit" value ="編集">
</p>
</form>
</body>
</html>

<?php


//入力したデータをsecletにより表示
$sql='SELECT*FROM mission';
$results=$pdo->query($sql);
        foreach($results as $row){
        //$rowの中にはテーブルのカラム名が入る
           echo $row['id'].' ';
           echo $row['name'].' ';
           echo $row['comment'].' ';
           echo $row['date'].'<br>';
       }

?>
