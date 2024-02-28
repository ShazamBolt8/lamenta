<?php

$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Category.php");
require_once("$server_root/classes/Author.php");
require_once("$server_root/classes/Article.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>
		<?php echo $site_name; ?>
	</title>
	<?php require_once("$server_root/components/metadata.php"); ?>
	<link rel="stylesheet" href="/src/style.css" />
</head>

<body>
	<div class="container">
		<!-- NavBar -->
		<?php require_once("$server_root/components/navbar.php"); ?>

		<div class="featured_container">
			<!--Featured-->
			<div class="box featured">
				<span class="head2 dim"><span class="material-symbols-rounded">award_star</span> Featured</span>
				<?php
				$article = new Article($conn);
				$randomArticle = $article->randomArticle();
				$randomArticleURL = $article->createURL($randomArticle['article_id'], $randomArticle['article_name'], $randomArticle['article_is_video']);
				echo <<<HTML
					<img src="{$randomArticle['article_cover_medium']}" alt="{$randomArticle['article_name']}" />
					<span class="head1">{$randomArticle['article_name']}</span>
					<a class="btn" href="{$randomArticleURL}">Learn More<span class="material-symbols-rounded">arrow_forward</span></a>
				HTML;
				?>
			</div>
			<!--/Featured-->

			<!--Theater-->
			<div class="box featured">
				<span class="head2 dim"><span class="material-symbols-rounded">theaters</span>Theater</span>
				<?php
				$randomVideoArticle = $article->randomVideoArticle();
				$randomVideoArticleURL = $article->createURL($randomVideoArticle['article_id'], $randomVideoArticle['article_name'], $randomVideoArticle['article_is_video']);
				echo <<<HTML
					{$randomVideoArticle['article_content']}
					<a class="btn" href="{$randomVideoArticleURL}">Watch Video<span class="material-symbols-rounded">play_arrow</span></a>
				HTML;
				?>
			</div>
			<!--/Theater-->
		</div>

		<div class="search_container">
			<div class="searchBox">
				<input id="searchBar" class="box search" type="text" placeholder="Search something new...">
				<span class="material-symbols-rounded">search</span>
			</div>
			<!--Articles-->
			<div class="articles_container">
				<?php
				$articles = $article->getAllArticles()['data'];
				foreach ($articles as $articleItem) {
					$articleItemURL = $article->createURL($articleItem['article_id'], $articleItem['article_name'], $articleItem['article_is_video']);
					echo <<<HTML
						<div class="box article">
							<a href="{$articleItemURL}" class="title head1">{$articleItem['article_name']}</a>
							<span data-pfp="{$articleItem['author_tiny_pfp']}" class="pfp"></span>
							<span class="material-symbols-rounded category">{$articleItem['category_icon']}</span>
						</div>
					HTML;
				}
				?>
			</div>
			<!--/Articles-->
		</div>

		<div class="option_container">
			<!--Authors-->
			<span class="heading head2 dim box">
				<span class="material-symbols-rounded">group</span>
				Authors
			</span>
			<?php
			$author = new Author($conn);
			$authorData = $author->getAllAuthors()['data'];
			foreach ($authorData as $authorProfile) {
				echo <<<HTML
						<div data-id="a{$authorProfile['author_id']}" class="choice box">
								<div data-pfp="{$authorProfile['author_tiny_pfp']}" class="pfp"></div>
								<span class="name head1">{$authorProfile['author_name']}</span>
						</div>
				HTML;
			}
			?>
			<!--/Authors-->

			<!--Categories-->
			<span class="heading head2 dim box">
				<span class="material-symbols-rounded">category</span>
				Categories
			</span>
			<?php
			$category = new Category($conn);
			$categoryData = $category->getAllCategories()['data'];
			foreach ($categoryData as $categoryItem) {
				echo <<<HTML
						<div data-id="c{$categoryItem['category_id']}" class="choice box">
								<div class="material-symbols-rounded dim">{$categoryItem['category_icon']}</div>
								<span class="name head1">{$categoryItem['category_name']}</span>
						</div>
				HTML;
			}
			?>
			<!--/Categories-->
		</div>
	</div>
	<script src="/src/main.js" type="module"></script>
</body>

</html>
<?php
$dbCon->closeConnection();
?>