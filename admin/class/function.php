<?php

class adminBlog
{
    private $conn;

    public function __construct()
    {
        // Database host, Database user, Database Pass, Database Name

        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = "";
        $dbname = 'little_butterfly';

        $this->conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

        if (!$this->conn) {
            die("Database Connection Error!!");
        }
    }

    public function admin_login($data)
    {
        $admin_email = $data['admin_email'];
        $admin_pass = md5($data['admin_pass']);

        $query = "SELECT * FROM admin_info WHERE admin_email='$admin_email' && admin_pass='$admin_pass'";

        if (mysqli_query($this->conn, $query)) {
            $admin_info = mysqli_query($this->conn, $query);

            if ($admin_info) {
                header("location: dashboard.php");
                $admin_data = mysqli_fetch_assoc($admin_info);
                session_start();
                $_SESSION['admin_id'] = $admin_data['id'];
                $_SESSION['admin_name'] = $admin_data['admin_name'];
            }
        }
    }

    public function adminLogout()
    {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        header('location:index.php');
    }

    public function add_category($data)
    {
        $cat_name = $data['cat_name'];
        $cat_desc = $data['cat_desc'];

        $query = "INSERT INTO category(cat_name, cat_desc) VALUE('$cat_name', '$cat_desc')";

        if (mysqli_query($this->conn, $query)) {
            return "Category Added Successfully";
        }
    }

    public function display_category()
    {
        $query = "SELECT * FROM category";

        if (mysqli_query($this->conn, $query)) {
            $category = mysqli_query($this->conn, $query);
            return $category;
        }
    }

    public function delete_category($id)
    {
        $query = "DELETE FROM category WHERE cat_id=$id";

        if (mysqli_query($this->conn, $query)) {
            return "Category Deleted Seccessfully";
        }
    }

    public function add_post($data)
    {
        $post_title = $data['post_title'];
        $post_content = $data['post_content'];
        $post_img = $_FILES['post_img']['name'];
        $post_img_tmp = $_FILES['post_img']['tmp_name'];
        $post_category = $data['post_category'];
        $post_summary = $data['post_summary'];
        $post_tag = $data['post_tag'];
        $post_status = $data['post_status'];

        $query = "INSERT INTO posts(post_title, post_content, post_img, post_ctg, post_author, post_date, post_comment_cnt, post_summary, post_tag, post_status) VALUES('$post_title', '$post_content', '$post_img', $post_category, 'Mou', now(), 3, '$post_summary', '$post_tag', $post_status)";

        if (mysqli_query($this->conn, $query)) {
            move_uploaded_file($post_img_tmp, '../uploads/' . $post_img);
            return "Post Added Successfully";
        }
    }

    public function display_posts()
    {
        $query = "SELECT * FROM post_with_ctg";

        if (mysqli_query($this->conn, $query)) {
            $posts = mysqli_query($this->conn, $query);
            return $posts;
        }
    }

    public function display_posts_public()
    {
        $query = "SELECT * FROM post_with_ctg WHERE post_status=1";

        if (mysqli_query($this->conn, $query)) {
            $posts = mysqli_query($this->conn, $query);
            return $posts;
        }
    }

    public function delete_posts($id)
    {
        $query = "DELETE FROM posts WHERE post_id=$id";

        if (mysqli_query($this->conn, $query)) {
            return "Post Deleted Seccessfully";
        }
    }

    public function edit_thumb($data)
    {
        $id = $data['edit_img_id'];
        $img_name = $_FILES['change_img']['name'];
        $img_tmp = $_FILES['change_img']['tmp_name'];

        $query = "UPDATE posts SET post_img='$img_name' WHERE post_id='$id'";

        if (mysqli_query($this->conn, $query)) {
            move_uploaded_file($img_tmp, '../uploads/' . $img_name);
            return "Thumbnail Updated Successfully";
        }
    }

    public function get_post_info($id)
    {
        $query = "SELECT * FROM post_with_ctg WHERE post_id='$id'";

        if (mysqli_query($this->conn, $query)) {
            $post_info = mysqli_query($this->conn, $query);
            $post = mysqli_fetch_assoc($post_info);
            return $post;
        }
    }

    public function update_post($data)
    {
        $post_id = $data['edit_post_id'];
        $change_title = $data['change_title'];
        $change_content = $data['change_content'];
        $change_summary = $data['change_summary'];

        $query = "UPDATE posts SET post_title='$change_title',post_summary='$change_summary',post_content='$change_content' WHERE post_id='$post_id'";

        if (mysqli_query($this->conn, $query)) {
            return "Post Updated Successfully!";
        }
    }
}
