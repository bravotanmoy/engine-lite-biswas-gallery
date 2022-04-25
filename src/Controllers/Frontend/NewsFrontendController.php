<?php
namespace Elab\Lite\Controllers\Frontend;

use Elab\Lite\System\Repository;
use Elab\Lite\System\FrontendController;

class NewsFrontendController extends FrontendController
{
    public function index_listing($page_id = null, $limit = false)
    {
        $e = $this->get_e('news');
        $where = $page_id ? "page=$page_id" : false;
        $elements = $e->find_elements($where, 'list', $limit);
        $this->assign(array(
            'page' => $page_id ? $e->get_element($page_id, 'id', 'full_url') : false,
            'elements' => $elements,
            'limit' => $limit,
            'total' => $e->count_elements($where),
        ));
    }
    
    public function listing()
    {
        $e = $this->get_e('news');
        $e->load_get_params();
        $page = $this->app->page['type'] == 'news' ? $this->app->page : false;
        $where = $page ? "page=$page[id]" : false;
        $elements = $e->list_elements($where, 'list', $page_info);

        foreach ($elements as &$element) {
            $element['published_time'] = date_format(new DateTime($element['date']), 'c');
        }

        $this->assign('news', array(
            'page' => $page,
            'elements' => $elements,
            'page_info' => $page_info,
        ));
    }

    /*
     * TODO: filtravimas pagal kalbas?
     */
    public function latest_news($page_size = 10)
    {
        $this->get_entity()->config['page_size'] = $page_size;
        $list = $this->get_entity()->list_elements('', array('list', 'full_url'));
        Repository::$smarty->assign('elements', $list);
    }

    public function element($element = false)
    {
        Repository::$smarty->assign('element', $element);
    }

    /**
     * Paruošia duomenis parodyti naujausių naujienų įrašų sąrašą su nuorodomis į pilnus straipsnius.
     * @param $category_id
     * @param $page_size
     * @return unknown_type
     */
    public function latest_news_list_by_category($category_id = false, $page_size = 10)
    {
        if (!$category_id) {
            $category_id = $this->get_frontend()->page['id'];
        }
        return $this->news_by_category($category_id, $page_size);
    }

    /**
     * Paruošia duomenis parodyti naujausių įrašų sąrašą su nuotrukomis ir trumpais aprašais/
     * @param $category_id
     * @param $page_size
     * @return unknown_type
     */
    public function news_by_category($categories_ids, $page_size = 10)
    {
        $page_size_prev = $this->get_entity()->config['page_size'] ? $this->get_entity()->config['page_size'] : 0;
        $this->get_entity()->config['page_size'] = $page_size;
        Repository::$smarty->assign(array(
            'news_records' => $this->get_entity()->list_elements("`page` IN ($categories_ids)", 'list'),
            'news_count' => $this->get_entity()->count_elements("`page` IN ($categories_ids)"),
        ));
        $this->get_entity()->config['page_size'] = $page_size_prev;
    }

    public function logic()
    {
        if (empty($this->path)) {
            $this->get_frontend()->set_content_type($this->get_name() . '/listing.tpl');
        } else {
            $this->get_frontend()->set_content_type($this->get_name() . '/detailed.tpl');
        }
    }
    
    public function detailed($element = false)
    {
        $f = $this->app;

        if ($element = $this->get_entity()->find_element("`url`='{$this->path[0]}'", 'detailed')) {
            $published_time = date_format(new DateTime($element['date']), 'c');

            $element['published_time'] = $published_time;

            $og_description = $element['meta_description'] ?: $element['description'];
            if (empty($og_description)) {
                $description = $f->load_helper()->display_html($element['text']);
                $description = strip_tags(html_entity_decode($description));

                $content = substr($description, 0, 300);
                $pos = strrpos($content, ' ');
                if ($pos > 0) {
                    $content = substr($content, 0, $pos);
                    $content .= '...';
                }

                $og_description = $content;
            }

            Repository::$smarty->assign('element', $element);
            $this->get_entity()->load_meta_fields($element, 'news');
            $f->add_page_path($element['name'], $element['full_url']);
        } else {
            $f->set_page_not_found();
        }
    }
}
