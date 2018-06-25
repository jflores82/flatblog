<?PHP

// Loads the blog class //
require_once('class.flatblog.php');

// Instantiates the new object //
$blog = new FlatBlog('posts');

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>tibone blog</title>
	</head>
	
	<body>
		<h1>BLOG</h1>
		
		<br><br>
		<?PHP $blog->showXPosts(5); ?>
	</body>
</html>
