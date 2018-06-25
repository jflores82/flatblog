<?PHP 
class FlatBlog { 
	/* 
		Flat_file simple blog.
		v20180618 - created by tibone 
		http://tibhub.classicgames.com.br/ 
		
		The idea is to have simple XML files. 
		To edit or delete a post, all you need to do, is edit or delete the corresponding xml file.
		The XML names aren't super important. Since the post ordenation is done by post-date.
		
		Why go flat-file? It's faster and easy to migrate between files. And if you live in the text-editor, you'll probably like to type straight into xml files. 
		There will be an admin interface soon.
		
		File will be commented later;
	*/
	var $post_dir; // subdirectory relative to this file, that all the post xml files reside on.
	var $validposts = array(); 
	var $allposts = array();
	
	public function __construct($post_dir) { 
		$this->post_dir = $post_dir; 
	}
	
	// Put all the files in an array.
	private function getPostFiles() {
		$posts = scandir($this->post_dir);
		foreach($posts as $postfile) { 
			if($postfile !== "." and $postfile !== "..") {
				if(substr($postfile, -3) == "xml") {
					array_push($this->validposts, $postfile);
				}
			}
		}
		return $posts;
	}

	// Put the contents from the files in a array, so we can order by date //
	private function getPosts() {
		$this->getPostFiles();
		foreach($this->validposts as $post) { 
			$xml = file_get_contents($this->post_dir.'/'.$post);
			$xml_obj = new SimpleXMLElement($xml);
			$postdate = (string)$xml_obj->postdate;
			$json = json_encode($xml_obj);
			$allposts[$postdate] = json_decode($json,TRUE);
		}
		krsort($allposts);
		return $allposts;
	}
	
	// Render the template as Pure HTML, replacing the appropriate tags. //
	private function renderPostTemplate() {
		$posts = $this->getPosts();
		$renderedposts = array();
		foreach($posts as $post) { 
			$post_template = file_get_contents('post.html');
			$title = $post['title'];
			$author = $post['author'];
			$postdate = $post['postdate'];
			$text = $post['text'];
			$text = nl2br($text);
			
			$post_template = str_replace("{{title}}", $title, $post_template);
			$post_template = str_replace("{{author}}", $author, $post_template);
			$post_template = str_replace("{{date}}", $postdate, $post_template);
			$post_template = str_replace("{{text}}", $text, $post_template);
			
			array_push($renderedposts, $post_template);
		}
		return $renderedposts;
	}
	
	// Show all posts, no restrictions (good for archive pages //
	public function showPosts() {
		$allposts = $this->renderPostTemplate();
		foreach($allposts as $post) {
			echo $post;
		}
	}
	
	// Show posts, $n being the number of posts to show and $o being offset (defaults to zero) //
	public function showXPosts($n, $o = 0) {
		$allposts = $this->renderPostTemplate();
		for($i = $o; $i < $n; $i++) {
			if(isset($allposts[$i])) { 
				echo $allposts[$i];
			}
		}
	}
	
	public function showRawPosts() { 
		$posts = $this->getPosts();
		return $posts;
	}

}
?>