    
<!-- Header #homepage -->
    <section class="header-homepage">
        <div class="container">
            <div class="row header-margin">
                <div class="col-sm-12">
                    <h1 class="hero-title"><?php echo get_languageword('Explore').' - '.get_languageword('Enrich').' - '.get_languageword('Excel');?></h1>
                    <p class="hero-tag"><?php echo get_languageword('Everything you need in order to find the')?> <strong><?php echo get_languageword('right'); ?></strong> <?php echo get_languageword('class for you');?></p>
                </div>
                <?php if(!$this->ion_auth->is_tutor()) { ?>
                <div class="col-sm-12">
                    <!-- Home Search form -->
                    <?php 
                          if(!empty($location_opts) || !empty($course_opts)): 
                            $this->load->view('sections/search_section_home', array('location_opts' => $location_opts, 'course_opts' => $course_opts), false);
                         endif;
                    ?>
                    <!-- Home Search form -->
                </div>
                <?php } ?>
                <div class="col-sm-12">
                    <img src="<?php echo URL_FRONT_IMAGES;?>headericons.png" alt="" class="img-responsive">
                </div>
            </div>
        </div>
    </section>
    <!-- Ends Header #homepage -->

    <!-- Advantages #homepage -->
    <?php if(strip_tags($this->config->item('site_settings')->advantages_section) == "On") { ?>
    <section>
        <div class="container">
            <div class="row row-margin">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="advantage">
                        <div class="media-left">
                            <img src="<?php echo URL_FRONT_IMAGES;?>icn-video.png" alt="..">
                        </div>
                        <div class="media-body">
                            <h4><a href=""><?php echo get_languageword('Videos').' & '.get_languageword('Images');?></a></h4>
                            <p><?php echo get_languageword('Listen to our teachers speeches and see our video testimonials before you take any decisions');?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="advantage">
                        <div class="media-left">
                            <img src="<?php echo URL_FRONT_IMAGES;?>icn-score.png" alt="..">
                        </div>
                        <div class="media-body">
                            <h4><a href=""><?php echo get_languageword('Quality Scores');?></a></h4>
                            <p><?php echo get_languageword('We have rated teachers for safety and convenience as we know how important this is for you')?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="advantage">
                        <div class="media-left">
                            <img src="<?php echo URL_FRONT_IMAGES;?>icn-calendar.png" alt="..">
                        </div>
                        <div class="media-body">
                            <h4><a href=""><?php echo get_languageword('Reviews').' & '.get_languageword('Ratings');?></a></h4>
                            <p><?php echo get_languageword('No more emails').', '.get_languageword('calls or messaging friends for recommendations').' - '.get_languageword('get acces to real reviews in seconds');?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>
    <!-- Ends Advantages #homepage -->


    <!-- Our-Popular #homepage -->
    <?php if(!empty($popular_courses)) { ?>
    <section class="our-popular">
        <div class="container">
            <div class="row-margin">
                <div class="row ">
                    <div class="col-sm-12 ">
                        <h2 class="heading"><?php echo get_languageword('our_popular_courses'); ?></h2>
                    </div>

                    <?php foreach ($popular_courses as $key => $courses) { 

                            $category = explode('_', $key);

                            //Category Details
                            $category_id   = $category[0];
                            $category_slug = $category[1];
                            $category_name = $category[2];

                        ?>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="pop-list">
                            <a href=<?php echo URL_HOME_ALL_COURSES.'/'.$category_slug;?> class="link-all"><?php echo get_languageword('see_all'); ?></a>
                            <h3 class="heading-line" title="<?php echo $category_name; ?>"><?php echo $category_name; ?></h3>
                            <ul>
                                <?php foreach ($courses as $key => $value) {

                                        $course   = explode('_', $value);
                                        //Course Details
                                        $course_id   = $course[0];
                                        $course_slug = $course[1];
                                        $course_name = $course[2];

                                 ?>
                                    <li><a href="<?php echo URL_HOME_SEARCH_TUTOR.'/'.$course_slug;?>" title="<?php echo $course_name; ?>"><?php echo $course_name; ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>

                    <?php } ?>

                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="mtop4">
                            <a href="<?php echo URL_HOME_ALL_COURSES; ?>" class="btn-link"><?php echo get_languageword('check_all_courses'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>
    <!-- Ends Our-Popular #homepage -->


    <!-- Featured-On #homepage -->
    <?php if(strip_tags($this->config->item('site_settings')->featured_on_section) == "On") { ?>
    <section class="featured-on">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                    <h4><?php echo get_languageword('FEATURED ON');?></h4>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                    <ul>
                        <li>
                            <a href="#"><img src="<?php echo URL_FRONT_IMAGES;?>1.png" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?php echo URL_FRONT_IMAGES;?>2.png" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?php echo URL_FRONT_IMAGES;?>3.png" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?php echo URL_FRONT_IMAGES;?>4.png" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?php echo URL_FRONT_IMAGES;?>5.png" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?php echo URL_FRONT_IMAGES;?>6.png" alt=""></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>
    <!-- Ends Featured-On #homepage -->

    <!-- Lession-cards #homepage -->
    <?php if(!empty($recent_courses)) { ?>
    <section class="lession-cards">
        <div class="container">
            <div class="row row-margin">
                <div class="col-sm-12 ">
                    <h2 class="heading"><?php echo get_languageword('Recent Added');?> <span><?php echo get_languageword('Courses');?></span></h2>
                </div>
                <?php foreach($recent_courses as $row) { ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="lession-card">
                        <a href="<?php echo URL_HOME_SEARCH_TUTOR.'/'.$row->slug;?>">
                            <figure class="imghvr-zoom-in">
                                <div class="card">
                                    <div class="card-img">
                                        <img src="<?php echo get_course_img($row->image); ?>" class="img-responsive" alt="">
                                        <figcaption></figcaption>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title" title="<?php echo $row->name;?>"><?php echo $row->name;?></h4>
                                        <p class="card-info animated fadeIn" title="<?php echo $row->description;?>"><?php if(!empty($row->description)) echo $row->description; else echo '&nbsp;';?></p>
                                    </div>
                                </div>
                            </figure>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <?php } ?>
    <!-- Ends Lession-cards #homepage -->

    <!-- How-it-works #homepage -->
    <?php $about_us_how_it_works = $this->base_model->get_page_how_it_works(); 

        if(!empty($about_us_how_it_works)) {

            echo $about_us_how_it_works[0]->description;
        }
    ?>
    <!-- Ends How-it-works #homepage -->

    <!-- Testimonial slider -->
    <div class="container" id='testimonials'>
        <div class="row row-margin">
            <div class="col-sm-12 ">
                <h2 class="heading"><?php echo get_languageword('Why Students');?> <span><?php echo get_languageword('Love Us');?></span></h2>
            </div>
            <div class="col-sm-12">
                <div class="testimonial-slider owl-theme">
                    <?php foreach($site_testimonials as $row) {?>
                    <div class=" item">
                        <div class="feedback-block">
                            <div class="comment">
                                <h4>“</h4>
                                <p><?php echo $row->comments;?></p>
                            </div>
                            
                                <div class="profile-block">
                                    <div class="media-left">
                                        <div class="profile-img">
                                            <img src="<?php if(isset($row->image)) echo  URL_PUBLIC_UPLOADS_TESTIMONIALS.'/'. $row->image;?>" alt=".." class="img-circle">
                                        </div>
                                    </div>
                                    <div class="media-body">

                                        <h4><?php echo $row->name;?></h4>
                                        <p><?php echo $row->position;?></p>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Ends Testimonial slider -->

    <!-- Counter #Homepage -->
    <?php $this->load->view('lesson_count.php'); ?>
    <!-- Counter #Homepage -->

    <?php if(!empty($home_tutor_ratings)) {?>
    <!-- Top-rated slider -->
    <section class="weekly-top-rated">
        <div class="container">
            <div class="row row-margin">
                <div class="col-md-12">
                    <h2 class="heading-border-btm"><?php echo get_languageword('weekly_top'); ?> <span><?php echo get_languageword('tutors'); ?></span></h2>
                    <div class="toprated-slider owl-theme">
                    <?php foreach($home_tutor_ratings as $rating) {
                            $hlink = URL_HOME_TUTOR_PROFILE.'/'.$rating->slug;
                        ?>
                        <div class="item">
                            <div class="profile-block">
                                <div class="media-left">
                                    <div class="profile-img">
                                        <a href="<?php echo $hlink; ?>">
                                           <img src="<?php echo get_tutor_img($rating->photo, $rating->gender); ?>" alt="" class="img-circle">
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <a href="<?php echo $hlink; ?>">
                                        <h4 title="<?php echo $rating->username;?>"><?php echo $rating->username;?></h4>
                                        <p><span><?php echo $rating->qualification;?></span></p>
                                        <div class="top_tutor_rating" <?php if(!empty($rating->rating)) echo 'data-score='.$rating->rating; ?> ></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
    
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Ends Top-rated slider -->
<?php } ?>
    <!-- Call-to-register -->
    <?php if(strip_tags($this->config->item('site_settings')->are_you_teacher_section) == "On") { ?>
    <div class="container">
        <div class="row row-margin">
            <div class="col-md-12">
                <h2 class="heading"><?php echo get_languageword('Are you a');?> <span><?php echo get_languageword('Teacher');?></span></h2>
                <p class="heading-tag"><?php echo get_languageword('Looking for a better way to reach your target audience').'? '.get_languageword('We can help').' - '.get_languageword('just list with us').' ';?><strong><?php echo get_languageword('for free');?></strong>.</p>
            </div>
            <div class="col-md-4 col-sm-4 col-sm-12">
                <div class="choose-block center-block">
                    <div class="icon">
                        <img src="<?php echo URL_FRONT_IMAGES;?>icn-cal.png" alt="">
                        <i class="sub-icon fa fa-check"></i>
                    </div>
                    <p><?php echo get_languageword('Boost your sales and scale up all of your classes')?></p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-sm-12">
                <div class="choose-block center-block">
                    <div class="icon">
                        <img src="<?php echo URL_FRONT_IMAGES;?>icn-group.png" alt="">
                        <i class="sub-icon fa fa-check"></i>
                    </div>
                    <p><?php echo get_languageword('Get a lot of exposure and brand recognition from everyone')?></p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-sm-12">
                <div class="choose-block center-block">
                    <div class="icon">
                        <img src="<?php echo URL_FRONT_IMAGES;?>icn-graph.png" alt="">
                        <i class="sub-icon fa fa-check"></i>
                    </div>
                    <p><?php echo get_languageword('Participate in various events and school programs whenever you want')?></p>
                </div>
            </div>
            <?php if (!$this->ion_auth->logged_in()){?>
            <div class="col-sm-12 text-center">
                <div class="mtop7 text-center">
                    <a href="<?php echo URL_AUTH_LOGIN; ?>" class="btn-link"><?php echo get_languageword('Register');?> <strong><?php echo get_languageword('as a Teacher');?></strong></a>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
    <?php } ?>
    <!-- Call-to-register -->


<link rel="stylesheet" href="<?php echo URL_FRONT_CSS;?>jquery.raty.css">
<script src="<?php echo URL_FRONT_JS;?>jquery.raty.js"></script>
<script>

    /****** Tutor Avg. Rating  ******/
   $('div.top_tutor_rating').raty({

    path: '<?php echo RESOURCES_FRONT;?>raty_images',
    score: function() {
      return $(this).attr('data-score');
    },
    readOnly: true
   });

   
</script>