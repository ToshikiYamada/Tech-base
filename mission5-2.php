<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>
            Mission5-1
        </title>
    </head>
    <body>

        <?php
        
        # DB接続設定
        $dsn='データベース名';
        $user='ユーザ名';
        $password='パスワード';
        $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        

        #テーブルを作成
        $sql='CREATE TABLE IF NOT EXISTS 掲示板'
        . "("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT"
        . ");";
        $stmt=$pdo -> query($sql);


        #変数の定義
        $name_input=filter_input(INPUT_POST, 'name');#名前
        $comment_input=filter_input(INPUT_POST, 'comment');#コメント
        $delete_input=filter_input(INPUT_POST, 'delete');#削除
        $edit_input=filter_input(INPUT_POST, 'edit');#編集
        $edit_number_input=filter_input(INPUT_POST, 'edit_number');#編集番号
        

        #新規投稿
        if(!empty($name_input && $comment_input) && empty($edit_number_input))
        {
            $sql=$pdo -> prepare("INSERT INTO 掲示板 (name, comment) VALUES (:name, :comment)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $name=$name_input;
            $comment=$comment_input;
            $sql -> execute();
        }

        #投稿削除
        if(!empty($delete_input))
        {
            $id=$delete_input;
            $sql='delete from 掲示板 where id=:id';
            $stmt=$pdo -> prepare($sql);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();
        }
        #編集内容
        $newnumber='';
        $newname='';
        $newcomment='';
        if(!empty($edit_input))
        {
            $sql='SELECT * FROM 掲示板';
            $stmt=$pdo -> query($sql);
            $results=$stmt -> fetchAll();
            foreach($results as $row)
            {
                if($row['id']==$edit_input)
                {
                    $newnumber=$row['id'];
                    $newname=$row['name'];
                    $newcomment=$row['comment'];
                }
            }
        }
        
        #投稿編集
        if(!empty($name_input && $comment_input && $edit_number_input))
        {
            $id=$edit_number_input;
            $name=$name_input;
            $comment=$comment_input;
            $sql='UPDATE 掲示板 SET name=:name, comment=:comment WHERE id=:id';
            $stmt=$pdo -> prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        ?>

        
        <h1><span style="border-bottom: solid 7px red;">投稿フォーム</span></h1>
        

        <form action="" method="post">
            <input type="text" name="name" placeholder="名前を入力"
             value="<?=$newname?>"><br>
            <input type="text" name="comment" placeholder="コメントを入力"
             value="<?=$newcomment?>">
            <input type="hidden" name="edit_number"
             value="<?=$newnumber?>"><br>
            <input type="submit" value="投稿"><br>
            <hr color ="red" width = "200px" align = "left">
            <input type="number" name="delete" placeholder="削除番号を入力"><br>
            <input type="submit" value="削除"><br>
            <hr color ="red" width = "200px" align = "left">
            <input type="number" name="edit" placeholder="編集番号を入力"><br>
            <input type="submit" value="編集"><br>
            <hr color ="red" width = "200px" align = "left">
        </form>
        
        
        <br>
        ---メッセージ---
        <br>
        <?php

        //表示
        $sql='SELECT * FROM 掲示板';
        $stmt=$pdo -> query($sql);
        $results=$stmt -> fetchAll();
        foreach($results as $row)
        {
            echo $row['id']. ' [';
            echo $row['name']. '] ';
            echo $row['comment']. '<br>';
        }
        //表示終了

        ?>

    </body>
</html>