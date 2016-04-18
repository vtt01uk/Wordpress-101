<?php



class Pixova_Lite_Widget_Latest_Posts extends WP_Widget {

    function Pixova_Lite_Widget_Latest_Posts() {
        $widget_ops = array( 
            'classname' => 'pixova_lite_widget_latest_posts',
            'description' => __('A widget that displays popular posts from blog', 'pixova-lite')
        );

        $control_ops = array( 
            'width' => 300, 
            'height' => 350, 
            'id_base' => 'pixova_lite_widget_latest_posts'
        );

        parent::__construct( 'pixova_lite_widget_latest_posts', __('[MT] - Latest Posts', 'pixova-lite'), $widget_ops, $control_ops );
    }


    function widget( $args, $instance ) {
        extract( $args );
        global $post;

        $title = apply_filters('widget_title', $instance['title'] );
        $items =  sanitize_key( $instance['items'] );

        if ($items == null){
            $items = 3;
        }

        echo $before_widget;

        if ( $title )
            echo $before_title . $title . $after_title;

        $popular_posts = new WP_Query('orderby=date&posts_per_page='.$items);
        $posts_count = 0;
        $extra_class = null;

        echo '<ul>';
        while ($popular_posts->have_posts()) : $popular_posts->the_post();
            global $post;
            $posts_count++;

            if ($posts_count == 1){
                $extra_class = 'first';
            }elseif($posts_count == $items){
                $extra_class = 'last';
            }else{
                $extra_class = null;
            }

            echo '<li class="fixed '.$extra_class.'">';


            echo '<h5><a href="'. get_permalink($post->ID) .'">'.$post->post_title.'</a></h5>';
                echo '<p>'.the_excerpt().'</p>';
            echo '</li>';

        endwhile;
        wp_reset_postdata();

        echo '</ul>';


        echo $after_widget;
    }


    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = esc_html( $new_instance['title'] );
        $instance['items'] = sanitize_key( $new_instance['items'] );

        return $instance;
    }


    function form( $instance ) {
        $defaults = array( 'title' => NULL, 'items' => 2);
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <div class="ewf-meta">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'pixova-lite'); ?></label>
                <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:100%;" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'items' ); ?>"><?php _e('How many post to show:', 'pixova-lite'); ?></label>

                <select id="<?php echo $this->get_field_id( 'items' ); ?>" name="<?php echo $this->get_field_name( 'items' ); ?>" style="width:100%;">
                    <?php

                    for($i = 1; $i <= 10; $i++){
                        if ($i == $instance['items']){
                            echo '<option  selected="selected">'.sanitize_key( $i ).'</option>';
                        }else{
                            echo '<option>'.sanitize_key( $i ).'</option>';
                        }
                    }

                    ?>
                </select>
            </p>
        </div>

    <?php
    }
}

// register the widget
    register_widget('pixova_lite_widget_latest_posts');