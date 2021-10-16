<?php
    include 'lib/secure.php';
    include 'lib/connect.php';
    include 'lib/queryArticle.php';
    include 'lib/article.php';

    $title = "";
    $body = "";
    $id = "";
    $title_alert = "";
    $body_alert = "";

    if (isset($_GET['id'])) {
      $queryArticle = new QueryArticle();
      $article = $queryArticle->find($_GET['id']);

      if ($article) {
        //編集する記事データが存在した時、フォームに埋め込む
        $id = $article->getId();
        $title = $article->getTitle();
        $body = $article->getBody();
      } else {
        header(('Location: backend.php'));
        exit;
      }

    } else if (!empty($_POST[('id')]) && !empty($_POST['title']) && !empty($_POST['body'])) {
      $title = $_POST['title'];
      $body = $_POST['body'];

      $queryArticle = new QueryArticle();
      $article = $queryArticle->find($_POST['id']);

      //記事のデータが存在するならば、タイトルと本文を保存して上書き
      if ($article) {
        $article->setTitle($title);
        $article->setBody($body);
        //画像がアップロードされていた時
        if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
          $article -> setFile($_FILES['image']);
        }
        $article->save();
      }
      header('Location: backend.php');
      exit;
    
    } else if (!empty($_POST)) {
      
      if (!empty($_POST['id'])) {
        $id = $_POST['id'];
      }
    } else {
      header('Location: backend.php');
    }

    if (!empty($_POST['title'])) {
      $title = $_POST['title'];
    } else {
      $title_alert = 'タイトルを入力して下さい';
    }

    if (!empty($_POST['body'])) {
      $body = $_POST['body'];
    } else {
      $body_alert = '本文を入力してください';
    }
?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Backend</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <style>
      body {
        padding-top: 5rem;
      }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .bg-red {
        background-color: #ff6644 !important;
      }
    </style>
    <link href="./css/blog.css" rel="stylesheet">
  </head>
  <body>

  <div class="col-md-12">
      <h1>記事編集</h1>
      <form action="edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <div class="mb-3">
          <label class="form-label"></label>

          <?php echo !empty($title_alert)? '<div class="alert alert-danger">'.$title_alert.'</div>': '' ?>
          <input type="text" name="title" value="<?php echo $title; ?>" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label"></label>
          <?php echo !empty($body_alert) ? '<div class="alert alert-danger">'.$body_alert.'</div>': ''?>
          <textarea name="body" class="form-control" rows="10"><?php echo $body; ?></textarea>
        </div>

        <?php if ($article->getFilename()) : ?>
          <div class="mb-3">
            <img src="/album/thumbs-<?php echo $article->getFilename() ?> ">
          </div>
          <?php endif ?>

          <div class="mb-3">
            <label class="form-label">画像</label>
            <input type="file" name="image" class="form-control">
          </div>

        <div class="mb-3">
          <button type="submit" class="btn btn-primary">投稿する</button>
        </div>
      </form>
  </div>
  </body>
</html>
