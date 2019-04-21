<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['member_login'])==false)
{
  print 'ようこそゲスト様　';
  print '<a href="member_login.html">会員ログイン</a><br/>';
  print '<br/>';
}
else
{
  print 'ようこそ';
  print $_SESSION['member_name'];
  print '様<br/>';
  print '<a href="member_logout.php">ログアウト</a><br/>';
  print '<br/>';
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>
<?php

  try{

    $cart=$_SESSION['cart'];
    $max=count($cart);

    if(isset($_SESSION['cart'])==true)
    {
      $cart=$_SESSION['cart'];
      $max=count($cart);
    }
    else
    {
      $max=0;
    }

    if($max==0)
    {
      print 'カートに商品が入っていません。<br/>';
      print '<br/>';
      print '<a href="shop_list.php">商品一覧へ戻る</a>';
      exit();
    }

    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    foreach($cart as $key=>$val)
    {
      $sql='SELECT code, name, price, gazou FROM mst_product WHERE code=?';
      $stmt=$dbh->prepare($sql);
      $data[0]=$val;
      $stmt->execute($data);

      $rec=$stmt->fetch(PDO::FETCH_ASSOC);

      $pro_name[]=$rec['name'];
      $pro_price[]=$rec['price'];
      if($rec['gazou']=='')
      {
        $pro_gazou[]='';
      }
      else
      {
        $pro_gazou[]='<img src="../product/gazou/'.$rec['gazou'].'" width="150" height="150">';
      }
    }
    $dbh=null;

  }
  catch(Exception $e)
  {
    print 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
  }

 ?>

 カートの中身<br/>
 <br/>
 <table border="1">
 <tr>
 <td>商品</td>
 <td>商品画像</td>
 <td>価格</td>
 <tr/>
 <?php for($i=0;$i<$max;$i++)
    {
 ?>
 <tr>
   <td><?php print $pro_name[$i]; ?></td>
   <td><?php print $pro_gazou[$i]; ?></td>
   <td><?php print $pro_price[$i]; ?>円</td>
 </tr>
 <?php
    }
 ?>
 </table>

 <form>
 <input type="button" onclick="history.back()" value="戻る">
 </form>

</body>
</html>
