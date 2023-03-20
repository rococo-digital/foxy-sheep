<?php
$logo_footer = get_field('logo_footer', 'options');
$contact_title = get_field('contact_us_title', 'options');
$quick_links_title = get_field('quick_links_title', 'options');
$quick_links = get_field('quick_links', 'options');

$telephone = get_field('telephone', 'options');
$email = get_field('email', 'options');
$address = get_field('address', 'options');

$copyright = get_field('copyright', 'options');
$siteby = get_field('site_by', 'options');

$show_on_page = get_field('show_on_page', 'options');

?>
<?php
    if(is_archive() || is_single() || is_product() || is_home() || is_page($show_on_page)){
        get_template_part('lib/partials/pre', 'footer');
    }
?>
</main>
    <footer class="mastfoot">
        <div class="mastfoot__top bg--green400">
            <div class="bg--svg">
                <svg xmlns="http://www.w3.org/2000/svg" width="273.035" height="263.802" viewBox="0 0 273.035 263.802">
                    <g id="Group_140" data-name="Group 140" transform="translate(-1229.965 -4811.561)" opacity="0.2">
                        <path id="Path_181" data-name="Path 181" d="M1388.642,4986.9c40.31-26.262,96.7-27.034,108.837-26.892,1.332-.017,3.2-.021,5.521.018v-62.7C1421.39,4922.357,1388.642,4986.9,1388.642,4986.9Z" fill="#4ca585"/>
                        <path id="Path_182" data-name="Path 182" d="M1297.771,5075.363h75.54c-7.792-29.418-7.889-52.407-7.889-53.431v-11.069c-.332-6.006-2.368-63.468,35.517-126.1-1.646.764-3.2,1.514-4.6,2.237l-.644.338c-.275.144-.551.286-.824.436a15.415,15.415,0,0,1-15.176.089q-11.442-5.895-22.915-11.726c-7.552-3.851-15.32-7.812-23.023-11.781-18.691,18.8-35.446,35.55-51.012,50.988,6.014,11.653,11.98,23.389,17.768,34.774l5.768,11.338c.329.646.641,1.307.96,1.986l.33.7a11.038,11.038,0,0,1-.156,9.811l-.385.751c-.309.606-.613,1.2-.928,1.788a213.455,213.455,0,0,0-16.407,39.641C1288.3,5038.3,1291.444,5057.957,1297.771,5075.363Z" fill="#4ca585"/>
                        <path id="Path_183" data-name="Path 183" d="M1289.691,5016.133a15.752,15.752,0,0,1-10.839,11.036q-11.388,3.644-22.749,7.359c-8.559,2.784-17.37,5.65-26.138,8.441.038,11.161.057,21.916.061,32.394h67.745C1291.444,5057.957,1288.3,5038.3,1289.691,5016.133Z" fill="#90d5ac"/>
                        <path id="Path_184" data-name="Path 184" d="M1461.38,4811.561c-2.787,8.723-5.619,17.431-8.37,25.892q-3.758,11.559-7.483,23.129a15.479,15.479,0,0,1-7.02,9.056c-.144.179-.292.358-.435.537q-1.306,1.625-2.57,3.25c-.127.163-.258.327-.384.49q-1.542,2-3.019,4c-.077.1-.151.207-.227.311q-1.416,1.924-2.775,3.85l-.146.208a229.876,229.876,0,0,0-40.309,104.619s32.748-64.545,114.358-89.573v-85.7C1488.6,4811.64,1474.8,4811.617,1461.38,4811.561Z" fill="#90d5ac"/>
                    </g>
                </svg>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mastfoot__columns">

                            <div class="mastfoot__logo">
                                <img src="<?php echo $logo_footer['url'];?>" alt="<?php echo $logo_footer['alt'];?>">
                                <?php social_icons('mastfoot__icons');?>
                            </div>
                            <div class="mastfoot__contact">
                                <h3 class="mastfoot__title"><?php echo $contact_title;?></h3>
                                <ul>
                                    <li>T: <a href="tel:<?php echo $telephone;?>"><?php echo $telephone;?></a></li>
                                    <li>E: <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></li>
                                    <li>A: <a href="https://goo.gl/maps/F8Gjzy7XGjhZMzDQ6" target="_blank"><?php echo $address;?></a></li>
                                </ul>
                            </div>
                            <div class="mastfoot__links">
                                <h3 class="mastfoot__title"><?php echo $quick_links_title;?></h3>

                                <ul>
                                    <?php foreach ($quick_links as $link){?>
                                        <li><a href="<?php echo get_permalink($link);?>"><?php echo get_the_title($link);?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mastfoot__bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-10">
                        <div class="mastfoot__copyright">
                            <?php echo $copyright;?>
                        </div>
                    </div>
                    <div class="col-md-2 text-md-end">
                        <p>Site by <a href="<?php echo $siteby['url'];?>" target="_blank"><?php echo $siteby['title'];?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>

	</body>
</html>