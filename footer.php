<?php

function make_footer_menu()
{
    $current_url = home_url(add_query_arg([], $_SERVER['REQUEST_URI']));
    $current_url = trailingslashit($current_url);


    // $items = wp_get_menu_array('footer-menu');
    $items = wp_get_nav_menu_items('footer-menu');

    $str = '<ul class="footer-menu">';
    foreach ($items as $item) {

        $is_current = trailingslashit($item->url) == $current_url;
        if ($is_current) {
            $item->classes[] = 'active';
        }
        $str .= "<li>";
        $str .= "<a href=" . $item->url . " ";
        $str .= 'class="' . implode(' ', $item->classes) . '"';
        $str .= " >";
        $str .= $item->title;
        $str .= "</a>";
        $str .= "</li>";
        // die();
    }


    $str .= "</ul>";
    return $str;
}


?>


</div>
<footer id="footer" role="contentinfo">

    <div class="footer-content">
        <?php echo make_footer_menu(); ?>
        <div class="contact-infos">
            <i class="fa fa-phone" aria-hidden="true"></i><span> (+33)6 88 05 54 41</span><br>
            <i class="fa fa-home" aria-hidden="true"></i><span> 26 canal St Martin</span> --
            <span> 35000 Rennes</span>
        </div>
    </div>
    <div id="copyright">
        &copy; <?php echo esc_html(date_i18n(__('Y', 'sprayloc-theme'))); ?> <?php echo esc_html(get_bloginfo('name')); ?>
    </div>
</footer>
</div>
<?php wp_footer(); ?>
</body>

</html>