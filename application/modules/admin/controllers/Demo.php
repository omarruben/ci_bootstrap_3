<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->mTitle = 'Demo - ';
		$this->push_breadcrumb('Demo');
	}

	public function index()
	{
		redirect('demo/item/1');
	}

	// Grocery CRUD - Blog Posts
	public function blog_post()
	{
		$crud = $this->crud->generate_crud('demo_blog_posts');
		$crud->columns('author_id', 'category_id', 'title', 'image_url', 'tags', 'publish_time', 'status');
		$crud->set_field_upload('image_url', UPLOAD_DEMO_BLOG_POST);
		$crud->set_relation('category_id', 'demo_blog_categories', 'title');
		$crud->set_relation_n_n('tags', 'demo_blog_posts_tags', 'demo_blog_tags', 'post_id', 'tag_id', 'title');
		
		$state = $crud->getState();
		if ($state==='add')
		{
			$crud->field_type('author_id', 'hidden', $this->mUser->id);
			$this->crud->unset_fields(array('status'));
		}
		else
		{
			$crud->set_relation('author_id', 'admin_users', '{first_name} {last_name}');
		}

		$this->mTitle.= 'Blog Posts';
		$this->mViewData['crud_data'] = $this->crud->render();
		$this->render('crud');
	}

	// Grocery CRUD - Blog Categories
	public function blog_category()
	{
		$crud = $this->crud->generate_crud('demo_blog_categories');
		$crud->columns('title');
		$this->mTitle.= 'Blog Categories';
		$this->mViewData['crud_note'] = btn('Sort Order', 'demo/blog_category_sortable');
		$this->mViewData['crud_data'] = $this->crud->render();
		$this->render('crud');
	}
	
	// Sortable - Blog Categories
	public function blog_category_sortable()
	{
		$this->load->library('sortable');
		$this->sortable->init('demo_blog_category_model');
		$this->mViewData['content'] = $this->sortable->render('{title}', 'demo/blog_category');
		$this->mTitle.= 'Blog Categories';
		$this->render('general');
	}

	// Grocery CRUD - Blog Tags
	public function blog_tag()
	{
		$crud = $this->crud->generate_crud('demo_blog_tags');
		$this->mTitle.= 'Blog Tags';
		$this->mViewData['crud_data'] = $this->crud->render();
		$this->render('crud');
	}

	// Image CRUD - Cover Photos
	public function cover_photo()
	{
		$crud = $this->crud->generate_image_crud('demo_cover_photos', 'image_url', UPLOAD_DEMO_COVER_PHOTO);
		$this->mTitle.= 'Cover Photos';
		$this->mViewData['crud_data'] = $crud->render();
		$this->render('crud');
	}
	
	// Simple page with parameter
	public function item($demo_id)
	{
		$this->mTitle.= 'Item '.$demo_id;
		$this->mViewData['demo_id'] = $demo_id;
		$this->render('demo/item');
	}
	
	// Pagination widget
	public function pagination()
	{
		$this->load->library('pagination');
		$this->mViewData['pagination'] = $this->pagination->render(200, 20);
		$this->mTitle.= 'Pagination';
		$this->render('demo/pagination');
	}
	
	// Sortable widget
	public function sortable()
	{
		$this->mViewData['entries'] = array(
			'Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5'
		);
		$this->mTitle.= 'Sortable';
		$this->render('demo/sortable');
	}
}
