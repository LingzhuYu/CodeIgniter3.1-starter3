<?php
/**
 * Created by PhpStorm.
 * User: Baik
 * Date: 10/20/2016
 * Time: 3:51 PM
 */
class Shopping extends Application {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        // What is the user up to?
        if ($this->session->has_userdata('order'))
            $this->keep_shopping();
        else $this->summarize();
    }
    public function summarize() {
        $this->data['pagebody'] = 'summary';
        $this->render('template');  // use the default template
    }

    public function neworder() {
        // create a new order if needed
        if (! $this->session->has_userdata('order')) {
            $order = new Order();
            $this->session->set_userdata('order', (array) $order);
        }

        $this->keep_shopping();
    }

    public function keep_shopping() {
        $order = new Order($this->session->userdata('order'));
        $stuff = $order->receipt();
        $this->data['receipt'] = $this->parsedown->parse($stuff);
        $this->data['content'] = '';
        // pictorial menu
        $count = 1;
        foreach ($this->categories->all() as $category) {
            $chunk = 'category' . $count;
            $this->data[$chunk] = $this->parser->parse('category-shop',$category,true);
            foreach($this->menu->all() as $menuitem) {
                if ($menuitem->category != $category->id) continue;
                $this->data[$chunk] .= $this->parser->parse('menuitem-shop',$menuitem,true);
            }
            $count++;
        }
        $this->render('template-shopping');

    }

    public function add($what) {
        $order = new Order($this->session->userdata('order'));
        $this->session->set_userdata('order',(array)$order);
        $order->additem($what);
        $this->keep_shopping();
        redirect('/shopping');
    }

    public function cancel() {
        // Drop any order in progress
        if ($this->session->has_userdata('order')) {
            $this->session->unset_userdata('order');
        }

        $this->index();
    }
}